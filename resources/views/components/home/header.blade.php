@props(['whatsappUrl'])

@php
    $isEnglish = app()->getLocale() === 'en';
    $links = collect(['services', 'projects', 'process', 'about', 'blog'])->map(fn (string $page) => [
        'href' => \App\Support\LocalizedRoute::to($page),
        'label' => __('site.nav.'.$page),
        'active' => \App\Support\LocalizedRoute::is($page) || ($page === 'projects' && \App\Support\LocalizedRoute::is('project')) || ($page === 'blog' && \App\Support\LocalizedRoute::is('post')),
    ]);
    $switchUrl = \App\Support\LocalizedRoute::alternate();
@endphp

<header x-data="{ scrolled: false, open: false }"
        x-init="scrolled = window.scrollY > 40"
        @scroll.window.passive="scrolled = window.scrollY > 40"
        class="fixed inset-x-0 top-0 z-50 transition-all duration-500"
        :class="scrolled ? 'pt-3' : 'pt-0'">
    <div class="mx-auto transition-all duration-500"
         :class="scrolled ? 'max-w-6xl px-3 sm:px-6' : 'max-w-7xl px-0'">
        <nav class="flex h-16 items-center justify-between gap-6 px-5 transition-all duration-500 sm:px-8"
             :class="scrolled ? 'rounded-full border border-line/80 bg-night/75 shadow-[0_20px_60px_-25px_rgba(0,0,0,0.8)] backdrop-blur-xl' : 'border border-transparent'">
            <a href="{{ $isEnglish ? route('home.en') : route('home') }}" class="group flex items-center gap-2.5" aria-label="Andrés Padovan">
                <x-brand-logo class="h-7 w-auto text-fg transition duration-300 group-hover:-translate-y-0.5" />
                <span class="font-mono text-sm font-semibold tracking-tight">andres<span class="text-malbec">.</span>dev<span class="animate-blink text-malbec">_</span></span>
            </a>

            <div class="hidden items-center gap-8 lg:flex">
                @foreach ($links as $link)
                    <a href="{{ $link['href'] }}" @class(['font-mono text-[13px] transition hover:text-fg', 'text-fg' => $link['active'], 'text-muted' => ! $link['active']]) @if ($link['active']) aria-current="page" @endif>
                        @if ($link['active'])<span class="text-malbec">/</span>@endif{{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="hidden items-center gap-4 lg:flex">
                <a href="{{ $switchUrl }}" class="font-mono text-[12px] tracking-widest text-muted uppercase transition hover:text-malbec" hreflang="{{ $isEnglish ? 'es' : 'en' }}">
                    {{ $isEnglish ? 'ES' : 'EN' }}
                </a>
                <a href="#contacto" class="btn btn-primary !px-4 !py-2.5">{{ __('site.nav.contact') }}</a>
            </div>

            <button type="button" class="grid size-10 place-items-center rounded-md border border-line lg:hidden" @click="open = !open" :aria-expanded="open" aria-label="{{ __('site.nav.menu') }}">
                <x-heroicon-o-bars-3 class="size-5" x-show="!open" />
                <x-heroicon-o-x-mark class="size-5" x-show="open" x-cloak />
            </button>
        </nav>

        <div x-show="open" x-cloak x-transition.opacity.duration.300ms @click.outside="open = false"
             class="mx-3 mt-2 rounded-2xl border border-line bg-night/95 p-6 backdrop-blur-xl lg:hidden">
            <div class="flex flex-col gap-5">
                @foreach ($links as $link)
                    <a href="{{ $link['href'] }}" @click="open = false" @class(['font-display text-xl uppercase', 'text-malbec' => $link['active']])>{{ $link['label'] }}</a>
                @endforeach
                <div class="flex items-center justify-between border-t border-line pt-5">
                    <a href="{{ $switchUrl }}" class="font-mono text-sm text-muted">{{ __('site.nav.switch') }}</a>
                    <a href="#contacto" @click="open = false" class="btn btn-primary !px-4 !py-2.5">{{ __('site.nav.contact') }}</a>
                </div>
            </div>
        </div>
    </div>
</header>
