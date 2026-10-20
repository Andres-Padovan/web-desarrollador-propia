@props(['profile'])

@php
    $words = explode(' ', mb_strtoupper(__('site.hero.title_b')));
    $shapes = [
        ['image' => '/'.config('profile.logo')],
        [['text' => mb_strtoupper(\Illuminate\Support\Str::before($profile['name'], ' ')), 'accent' => false], ['text' => mb_strtoupper(\Illuminate\Support\Str::after($profile['name'], ' ')), 'accent' => true]],
        [
            ['text' => mb_strtoupper(__('site.hero.title_a')), 'accent' => false],
            ['text' => array_shift($words), 'accent' => true],
            ['text' => implode(' ', $words), 'accent' => true],
        ],
    ];
@endphp

{{--
    Entrada al abrir la web: miles de caracteres de código se juntan y escriben el nombre,
    reaccionan al mouse y explotan al hacer scroll o clic. Aparece en la primera visita, al recargar la portada
    y al tocar el logo; nunca con "reducir movimiento". Sin JS no se muestra.
--}}
<script>
    (() => {
        try {
            const reloaded = performance.getEntriesByType('navigation')[0]?.type === 'reload';
            const fromLogo = sessionStorage.getItem('intro-replay');
            sessionStorage.removeItem('intro-replay');
            if ((sessionStorage.getItem('intro-seen') && !reloaded && !fromLogo) || matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        } catch (error) {
            return;
        }
        document.documentElement.classList.add('has-intro');
        setTimeout(() => {
            if (!window.__introStarted) document.documentElement.classList.remove('has-intro');
        }, 6000);
    })();
</script>

<div data-intro data-shapes='@json($shapes)' class="intro fixed inset-0 z-[90] touch-none overflow-hidden bg-ink" role="dialog" aria-label="{{ $profile['name'] }}">
    <canvas data-intro-canvas class="absolute inset-0 size-full"></canvas>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_40%,rgba(9,9,13,0.85)_100%)]"></div>

    <div class="pointer-events-none absolute inset-x-0 top-0 flex items-center justify-between p-5 font-mono text-xs text-dim sm:p-8">
        <span class="flex items-center gap-2"><span class="size-1.5 animate-pulse rounded-full bg-malbec"></span> {{ $profile['handle'] }}.dev</span>
        <button type="button" data-intro-skip class="pointer-events-auto cursor-pointer tracking-widest uppercase transition hover:text-malbec">{{ __('site.intro.skip') }} ⏎</button>
    </div>

    <p data-intro-name class="intro-name pointer-events-none absolute inset-x-5 bottom-[16vh] text-center font-mono text-[11px] tracking-[0.25em] text-muted uppercase sm:bottom-[13vh] sm:text-xs">
        <span class="text-malbec">{{ __('site.hero.kicker') }}</span> · {{ $profile['location'] }}
    </p>

    <div class="pointer-events-none absolute bottom-5 left-5 hidden font-mono sm:block text-[11px] leading-relaxed text-muted sm:bottom-8 sm:left-8 sm:text-xs" data-intro-log
         data-lines='@json(__('site.intro.lines'))' data-ready="{{ __('site.intro.ready') }}"></div>

    <button type="button" data-intro-hint data-intro-enter class="intro-hint absolute right-5 bottom-5 flex cursor-pointer items-center gap-3 font-mono text-[11px] tracking-[0.2em] text-fg uppercase pointer-coarse:inset-x-5 pointer-coarse:justify-center pointer-coarse:rounded-full pointer-coarse:border pointer-coarse:border-malbec/60 pointer-coarse:bg-ink/70 pointer-coarse:py-3 pointer-coarse:backdrop-blur sm:right-8 sm:bottom-8">
        <span class="relative flex h-7 w-4.5 justify-center rounded-full border border-fg/50 pointer-coarse:hidden"><span class="mt-1.5 h-1.5 w-0.5 animate-bounce rounded-full bg-malbec"></span></span>
        <span class="pointer-coarse:hidden">{{ __('site.intro.enter') }}</span>
        <span class="hidden pointer-coarse:inline">{{ __('site.intro.enter_touch') }} ↓</span>
    </button>
</div>
