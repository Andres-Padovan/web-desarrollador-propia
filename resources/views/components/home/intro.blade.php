@props(['profile'])

{{--
    Entrada al abrir la web: miles de caracteres de código se juntan y arman el retrato,
    reaccionan al mouse y explotan al hacer scroll o clic. Una vez por visita (sessionStorage)
    y nunca con "reducir movimiento". Sin JS no se muestra.
--}}
<script>
    (() => {
        try {
            if (sessionStorage.getItem('intro-seen') || matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        } catch (error) {
            return;
        }
        document.documentElement.classList.add('has-intro');
        setTimeout(() => {
            if (!window.__introStarted) document.documentElement.classList.remove('has-intro');
        }, 6000);
    })();
</script>

<div data-intro data-portrait="{{ asset('images/intro-portrait.png') }}" class="intro fixed inset-0 z-[90] overflow-hidden bg-ink" role="dialog" aria-label="{{ $profile['name'] }}">
    <canvas data-intro-canvas class="absolute inset-0 size-full"></canvas>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_40%,rgba(9,9,13,0.85)_100%)]"></div>

    <div class="pointer-events-none absolute inset-x-0 top-0 flex items-center justify-between p-5 font-mono text-xs text-dim sm:p-8">
        <span class="flex items-center gap-2"><span class="size-1.5 animate-pulse rounded-full bg-malbec"></span> {{ $profile['handle'] }}.dev</span>
        <button type="button" data-intro-skip class="pointer-events-auto cursor-pointer tracking-widest uppercase transition hover:text-malbec">{{ __('site.intro.skip') }} ⏎</button>
    </div>

    <div data-intro-name class="intro-name pointer-events-none absolute inset-x-5 bottom-20 sm:inset-x-auto sm:right-[7vw] sm:bottom-auto sm:top-1/2 sm:max-w-[44vw] sm:-translate-y-1/2">
        <p class="eyebrow mb-4">{{ __('site.hero.kicker') }}</p>
        <h1 class="font-display text-[11vw] leading-[0.92] font-black tracking-tight uppercase sm:text-[5.6vw]">
            <span class="intro-word block">{{ \Illuminate\Support\Str::before($profile['name'], ' ') }}</span>
            <span class="intro-word block text-malbec">{{ \Illuminate\Support\Str::after($profile['name'], ' ') }}</span>
        </h1>
        <p class="mt-5 font-mono text-xs tracking-[0.2em] text-muted uppercase">{{ $profile['location'] }} · {{ $profile['years_experience'] }} {{ __('site.intro.years') }}</p>
    </div>

    <div class="pointer-events-none absolute bottom-5 left-5 hidden font-mono sm:block text-[11px] leading-relaxed text-muted sm:bottom-8 sm:left-8 sm:text-xs" data-intro-log
         data-lines='@json(__('site.intro.lines'))' data-ready="{{ __('site.intro.ready') }}"></div>

    <div data-intro-hint class="intro-hint pointer-events-none absolute right-5 bottom-5 flex items-center gap-3 font-mono text-[11px] tracking-[0.2em] text-fg uppercase sm:right-8 sm:bottom-8">
        <span class="relative flex h-7 w-4.5 justify-center rounded-full border border-fg/50"><span class="mt-1.5 h-1.5 w-0.5 animate-bounce rounded-full bg-malbec"></span></span>
        {{ __('site.intro.enter') }}
    </div>
</div>
