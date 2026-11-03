@props(['rating' => 0])
@php $r = round(((float) $rating) * 2) / 2; @endphp
<div class="star-container flex items-center">
    @for ($i = 1; $i <= 5; $i++)
        <div class="flex shrink-0 w-[18px] h-[18px] p-[2px]">
            @if ($r >= $i)
                <img src="{{ asset('assets/images/icons/Star.svg') }}" alt="star">
            @elseif ($r >= $i - 0.5)
                <img src="{{ asset('assets/images/icons/Star-half.svg') }}" alt="star">
            @else
                <img src="{{ asset('assets/images/icons/Star.svg') }}" alt="star" class="opacity-20">
            @endif
        </div>
    @endfor
</div>
