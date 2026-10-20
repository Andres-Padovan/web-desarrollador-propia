@props(['whatsappUrl'])

@php
    $isEnglish = app()->getLocale() === 'en';
    $links = [
        '#servicios' => __('site.nav.services'),
        '#proyectos' => __('site.nav.projects'),
        '#proceso' => __('site.nav.process'),
        '#sobre-mi' => __('site.nav.about'),
    ];
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
                <span class="grid size-8 place-items-center rounded-md bg-malbec font-display text-[13px] font-black text-ink transition group-hover:rotate-6">AP</span>
                <span class="font-mono text-sm font-semibold tracking-tight">andres<span class="text-malbec">.</span>dev<span class="animate-blink text-malbec">_</span></span>
            </a>

            <div class="hidden items-center gap-8 lg:flex">
                @foreach ($links as $href => $label)
                    <a href="{{ $href }}" class="font-mono text-[13px] text-muted transition hover:text-fg">{{ $label }}</a>
                @endforeach
            </div>

            <div class="hidden items-center gap-4 lg:flex">
                <a href="{{ $isEnglish ? route('home') : route('home.en') }}" class="font-mono text-[12px] tracking-widest text-muted uppercase transition hover:text-malbec" hreflang="{{ $isEnglish ? 'es' : 'en' }}">
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
                @foreach ($links as $href => $label)
                    <a href="{{ $href }}" @click="open = false" class="font-display text-xl uppercase">{{ $label }}</a>
                @endforeach
                <div class="flex items-center justify-between border-t border-line pt-5">
                    <a href="{{ $isEnglish ? route('home') : route('home.en') }}" class="font-mono text-sm text-muted">{{ __('site.nav.switch') }}</a>
                    <a href="#contacto" @click="open = false" class="btn btn-primary !px-4 !py-2.5">{{ __('site.nav.contact') }}</a>
                </div>
            </div>
        </div>
    </div>
</header>
