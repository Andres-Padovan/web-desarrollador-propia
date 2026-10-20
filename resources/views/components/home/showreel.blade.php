@props(['profile'])

@php
    // Código que se "escribe" en la pantalla de la laptop. Cada línea ya viene coloreada.
    $code = [
        '<span class="text-dim">// routes/web.php</span>',
        '<span class="text-malbec">Route</span>::<span class="text-sun">get</span>(<span class="text-leaf">\'/\'</span>, <span class="text-malbec">fn</span> () => <span class="text-sun">view</span>(<span class="text-leaf">\'home\'</span>, [',
        '    <span class="text-leaf">\'proyectos\'</span> => <span class="text-malbec">Project</span>::<span class="text-sun">published</span>()-><span class="text-sun">get</span>(),',
        ']));',
        '',
        '<span class="text-dim">// resources/views/home.blade.php</span>',
        '&lt;<span class="text-malbec">x-hero</span> <span class="text-sun">titulo</span>=<span class="text-leaf">"Tu idea, online"</span> /&gt;',
        '<span class="text-malbec">@</span><span class="text-malbec">foreach</span> ($proyectos <span class="text-malbec">as</span> $proyecto)',
        '    &lt;<span class="text-malbec">x-card</span> :$proyecto /&gt;',
        '<span class="text-malbec">@</span><span class="text-malbec">endforeach</span>',
        '',
        '<span class="text-dim">$</span> php artisan <span class="text-sun">deploy</span> <span class="text-leaf">--produccion</span>',
    ];
    $steps = __('site.showreel.steps');
@endphp

<section data-showreel class="showreel relative h-[460vh] bg-ink" aria-label="{{ __('site.showreel.title') }}">
    <div class="sticky top-0 flex h-svh items-center justify-center overflow-hidden">
        <div class="grid-bg pointer-events-none absolute inset-0 opacity-60"></div>
        <div class="pointer-events-none absolute top-1/2 left-1/2 h-[60vh] w-[70vw] -translate-x-1/2 -translate-y-1/2 rounded-full bg-malbec/15 blur-[150px]"></div>

        {{-- Título de la escena: se va cuando la laptop termina de abrirse --}}
        <div class="showreel-heading pointer-events-none absolute inset-x-0 top-[12vh] z-10 text-center">
            <p class="eyebrow justify-center">{{ __('site.showreel.eyebrow') }}</p>
            <h2 class="section-title mx-auto mt-4 max-w-4xl px-5">{{ __('site.showreel.title') }}</h2>
        </div>

        {{-- Pasos gigantes a los costados --}}
        @foreach ($steps as $index => $step)
            <div class="showreel-step pointer-events-none absolute z-20 max-w-[26vw] {{ $index % 2 ? 'right-[4vw] text-right' : 'left-[4vw]' }} bottom-[7vh] max-md:inset-x-5 max-md:max-w-none max-md:text-left lg:top-1/2 lg:bottom-auto lg:-translate-y-1/2"
                 data-step="{{ $index }}">
                <p class="font-mono text-sm text-malbec">{{ $step['n'] }} /</p>
                <p class="font-display text-[11vw] leading-none font-black tracking-tight uppercase lg:text-[3.5vw]">{{ $step['title'] }}</p>
                <p class="mt-3 text-muted max-md:text-sm">{{ $step['text'] }}</p>
            </div>
        @endforeach

        {{-- Laptop --}}
        <div class="showreel-stage relative z-10 w-[min(92vw,58rem)] max-md:-mt-[18vh] lg:w-[min(52vw,58rem)]" style="perspective: 1600px">
            <div class="showreel-laptop" style="transform-origin: 50% 100%">
                <div class="rounded-t-[1.4rem] border border-line bg-[#0c0c11] p-[1.6%] shadow-[0_40px_120px_-30px_rgba(255,61,127,0.45)]">
                    <div class="relative aspect-[16/10] overflow-hidden rounded-md bg-night">
                        {{-- Capa 1: editor --}}
                        <div class="showreel-editor absolute inset-0 flex flex-col">
                            <div class="flex items-center gap-1.5 border-b border-line px-3 py-2">
                                <span class="size-2 rounded-full bg-[#ff5f57]"></span><span class="size-2 rounded-full bg-[#febc2e]"></span><span class="size-2 rounded-full bg-[#28c840]"></span>
                                <span class="ml-3 font-mono text-[10px] text-dim">{{ $profile['handle'] }}@mendoza: ~/proyecto</span>
                            </div>
                            <div class="flex-1 overflow-hidden p-[3%] font-mono text-[clamp(7px,1.15vw,15px)] leading-[1.7]">
                                @foreach ($code as $number => $line)
                                    @php $length = mb_strlen(html_entity_decode(strip_tags($line))); @endphp
                                    <div class="flex gap-4">
                                        <span class="w-5 shrink-0 text-right text-dim/60 select-none">{{ $number + 1 }}</span>
                                        <span class="code-line" data-length="{{ $length }}" style="--chars: 0">{!! $line !!}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Capa 2: los sitios reales, que se recorren solos dentro de la pantalla --}}
                        @foreach ($profile['showreel'] as $site)
                            <div class="showreel-site absolute inset-0 overflow-hidden bg-ink opacity-0" data-site="{{ $loop->index }}">
                                <img src="{{ asset($site['image']) }}" alt="{{ $site['title'] }}" loading="lazy" class="w-full will-change-transform">
                                <span class="absolute bottom-3 left-3 rounded-sm border border-line bg-ink/85 px-2 py-1 font-mono text-[10px] tracking-widest text-fg uppercase backdrop-blur">
                                    <span class="text-leaf">●</span> {{ $site['title'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative mx-[-6%] h-[clamp(10px,1.6vw,22px)] rounded-b-[1.2rem] bg-gradient-to-b from-[#2a2a3a] to-[#14141c]">
                    <span class="absolute top-0 left-1/2 h-1/2 w-[16%] -translate-x-1/2 rounded-b-lg bg-[#0c0c11]"></span>
                </div>
            </div>
        </div>
    </div>
</section>
