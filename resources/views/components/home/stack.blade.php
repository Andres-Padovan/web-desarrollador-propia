@props(['profile'])

<div class="relative overflow-hidden border-y border-line/60 bg-night py-8" aria-label="Stack">
    <div class="pointer-events-none absolute inset-y-0 left-0 z-10 w-24 bg-gradient-to-r from-night to-transparent"></div>
    <div class="pointer-events-none absolute inset-y-0 right-0 z-10 w-24 bg-gradient-to-l from-night to-transparent"></div>
    <div class="flex w-max animate-marquee hover:[animation-play-state:paused]">
        @foreach ([1, 2] as $copy)
            <ul class="flex shrink-0 items-center gap-12 pr-12" @if ($copy === 2) aria-hidden="true" @endif>
                @foreach ($profile['stack'] as $tech)
                    <li class="flex items-center gap-12 font-display text-2xl font-bold tracking-tight whitespace-nowrap text-dim uppercase transition hover:text-fg sm:text-3xl">
                        {{ $tech }} <span class="text-base text-malbec">✦</span>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>
</div>
