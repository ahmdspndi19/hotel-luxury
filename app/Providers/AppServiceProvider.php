<?php

namespace App\Providers;

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });

        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // kebijakan password: lebih ketat di production
        Password::defaults(fn () => $this->app->isProduction()
            ? Password::min(8)->letters()->mixedCase()->numbers()
            : Password::min(8));

        $this->registerRateLimiters();

        // header keamanan dasar untuk semua response grup "web"
        $this->app['router']->pushMiddlewareToGroup('web', SecurityHeaders::class);
    }

    private function registerRateLimiters(): void
    {
        $key = fn (Request $request) => $request->user()?->id ?: $request->ip();

        // pencarian hotel (publik)
        RateLimiter::for('search', fn (Request $request) => Limit::perMinute(30)->by($request->ip()));

        // membuat booking
        RateLimiter::for('booking', fn (Request $request) => Limit::perMinute(10)->by($key($request)));

        // upload bukti bayar
        RateLimiter::for('payment-upload', fn (Request $request) => Limit::perMinute(5)->by($key($request)));

        // pendaftaran akun baru (cegah pembuatan akun massal)
        RateLimiter::for('register', fn (Request $request) => Limit::perHour(10)->by($request->ip()));
    }
}
