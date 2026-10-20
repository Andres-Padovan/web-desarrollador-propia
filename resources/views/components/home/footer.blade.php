@props(['profile'])

@php
    $isEnglish = app()->getLocale() === 'en';
@endphp

<footer class="border-t border-line bg-night">
    <div class="container-site flex flex-col gap-14 py-16">
        <div class="grid gap-12 md:grid-cols-[1.4fr_1fr_1fr]">
            <div class="flex flex-col gap-4">
                <a href="{{ $isEnglish ? route('home.en') : route('home') }}" class="flex items-center gap-2.5">
                    <span class="grid size-9 place-items-center rounded-md bg-malbec font-display text-sm font-black text-ink">AP</span>
                    <span class="font-display text-lg font-bold uppercase">{{ $profile['name'] }}</span>
                </a>
                <p class="max-w-xs text-muted">{{ __('site.footer.tagline') }}</p>
                <a href="mailto:{{ $profile['email'] }}" class="font-mono text-sm text-fg/80 transition hover:text-malbec">{{ $profile['email'] }}</a>
            </div>

            <nav class="flex flex-col gap-3" aria-label="{{ __('site.footer.nav') }}">
                <p class="font-mono text-[11px] tracking-[0.2em] text-dim uppercase">{{ __('site.footer.nav') }}</p>
                <a href="#servicios" class="text-muted transition hover:text-fg">{{ __('site.nav.services') }}</a>
                <a href="#proyectos" class="text-muted transition hover:text-fg">{{ __('site.nav.projects') }}</a>
                <a href="#proceso" class="text-muted transition hover:text-fg">{{ __('site.nav.process') }}</a>
                <a href="#sobre-mi" class="text-muted transition hover:text-fg">{{ __('site.nav.about') }}</a>
                <a href="#contacto" class="text-muted transition hover:text-fg">{{ __('site.nav.contact') }}</a>
            </nav>

            <div class="flex flex-col gap-3">
                <p class="font-mono text-[11px] tracking-[0.2em] text-dim uppercase">{{ __('site.footer.social') }}</p>
                <a href="{{ $profile['linkedin'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-muted transition hover:text-fg"><x-brand-icon name="linkedin" class="size-4" /> LinkedIn</a>
                <a href="{{ $profile['github'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-muted transition hover:text-fg"><x-brand-icon name="github" class="size-4" /> GitHub</a>
                <a href="https://wa.me/{{ $profile['whatsapp'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-muted transition hover:text-fg"><x-brand-icon name="whatsapp" class="size-4" /> WhatsApp</a>
                <a href="{{ $isEnglish ? route('home') : route('home.en') }}" class="mt-2 font-mono text-xs text-malbec">{{ __('site.nav.switch') }} →</a>
            </div>
        </div>

        <div class="flex flex-col justify-between gap-3 border-t border-line pt-8 font-mono text-xs text-dim sm:flex-row">
            <p>© {{ now()->year }} {{ $profile['name'] }}. {{ __('site.footer.rights') }}</p>
            <p>{{ __('site.footer.made') }} <span class="text-malbec">●</span></p>
        </div>
    </div>
</footer>
