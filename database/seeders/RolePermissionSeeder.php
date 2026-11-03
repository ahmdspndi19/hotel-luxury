<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage countries',
            'manage cities',
            'manage hotels',
            'manage hotel bookings',
            'checkout hotels',
            'view hotel bookings',
        ];

        foreach($permissions as $perm){
            Permission::firstOrCreate([
                'name' => $perm
            ]);
        }

        $customerRole = Role::firstOrCreate([
            'name' => 'customer'
        ]);

        $customerPermissions = [
            'checkout hotels',
            'view hotel bookings',
        ];

        $customerRole->syncPermissions($customerPermissions);

        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin'
        ]);

        // Email/password admin dari .env (ADMIN_EMAIL, ADMIN_PASSWORD). Password kosong: dibuat acak dan ditampilkan sekali.
        // PENTING: jalankan db:seed SEBELUM `config:cache`, karena env() bernilai null setelah config di-cache.
        $password = env('ADMIN_PASSWORD') ?: Str::random(16);

        $user = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'super@admin.com')],
            [
                'name' => 'Super Admin',
                'avatar' => 'images/dummyavatar.png',
                'password' => bcrypt($password),
            ]
        );

        if ($user->wasRecentlyCreated && ! env('ADMIN_PASSWORD')) {
            $this->command?->warn("Password admin (catat sekarang): {$password}");
        }

        $user->assignRole($superAdminRole);

    }
}
