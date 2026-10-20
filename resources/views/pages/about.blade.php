@php
    $profile = config('profile');
    $en = app()->getLocale() === 'en';
    $gpa = $en ? str_replace(',', '.', $profile['gpa']) : $profile['gpa'];

    /*
     * Código del editor lateral, por bloques: cada bloque se escribe cuando aparece su sección.
     * Cada línea es una lista de [tipo, texto]; el tipo define el color.
     */
    $str = fn (string $text) => ['s', "'".$text."'"];
    $prop = fn (string $name) => [['x', '  '], ['p', $name], ['x', ': ']];
    $projectNames = $projects->map(fn ($project) => \Illuminate\Support\Str::before($project->localized('title'), ' · '))->unique()->take(5)->values();

    $blocks = [
        [
            [['c', $en ? '// about.ts' : '// sobre-mi.ts']],
            [['k', 'const '], ['p', 'andres'], ['x', ' = {']],
            [...$prop($en ? 'role' : 'rol'), $str($en ? 'Systems analyst & developer' : 'Analista y programador'), ['x', ',']],
            [...$prop($en ? 'base' : 'base'), $str('San Martín, Mendoza'), ['x', ',']],
            [...$prop($en ? 'experience' : 'experiencia'), ['n', (string) $profile['years_experience']], ['x', ', '], ['c', $en ? '// years' : '// años']],
            [['x', '};']],
        ],
        [
            [],
            [['p', 'andres'], ['x', '.'], ['p', $en ? 'education' : 'formacion'], ['x', ' = {']],
            [...$prop($en ? 'degree' : 'titulo'), $str($en ? 'Higher Technician' : 'Técnico Superior'), ['x', ',']],
            [...$prop($en ? 'field' : 'carrera'), $str($en ? 'Systems Analysis & Dev' : 'Análisis y Programación'), ['x', ',']],
            [...$prop($en ? 'gpa' : 'promedio'), ['n', str_replace(',', '.', $profile['gpa'])], ['x', ',']],
            [['x', '};']],
        ],
        [
            [],
            [['p', 'andres'], ['x', '.'], ['p', $en ? 'principles' : 'principios'], ['x', ' = [']],
            ...collect(__('site.about.principles'))->map(fn (array $principle) => [['x', '  '], $str($principle['code']), ['x', ',']])->all(),
            [['x', '];']],
        ],
        [
            [],
            [['p', 'andres'], ['x', '.'], ['p', $en ? 'projects' : 'proyectos'], ['x', ' = [']],
            ...$projectNames->map(fn (string $name) => [['x', '  '], $str($name), ['x', ',']])->all(),
            [['x', '];']],
        ],
        [
            [],
            [['k', 'await '], ['p', 'andres'], ['x', '.'], ['p', $en ? 'start' : 'arrancar'], ['x', '('], ['p', $en ? 'yourProject' : 'tuProyecto'], ['x', ');']],
            [['c', $en ? '// → shall we talk?' : '// → ¿hablamos?']],
        ],
    ];
    $colors = ['k' => 'text-malbec', 'p' => 'text-fg', 's' => 'text-sun', 'n' => 'text-malbec-soft', 'c' => 'text-dim', 'x' => 'text-muted'];
    $lineNumber = 0;
@endphp

<x-layouts.site :title="__('site.pages.about.title')" :description="__('site.pages.about.description')">
    <x-slot:before>
        {{-- Telón: se abre al entrar y deja ver la página --}}
        <div class="about-curtain" aria-hidden="true">
            <div class="about-curtain-panel about-curtain-left"></div>
            <div class="about-curtain-panel about-curtain-right"></div>
            <p class="about-curtain-text font-mono text-sm text-muted"><span class="text-malbec">$</span> open ./{{ $en ? 'about' : 'sobre-mi' }}</p>
        </div>
    </x-slot:before>

    <div class="relative overflow-hidden pt-28 sm:pt-36" x-data="aboutCode">
        <div class="grid-bg pointer-events-none absolute inset-x-0 top-0 h-[900px]"></div>
        <div class="pointer-events-none absolute -top-40 -left-20 h-[520px] w-[720px] rounded-full bg-malbec/15 blur-[150px]"></div>

        <div class="container-site relative grid gap-y-24 pb-28 sm:gap-y-32 lg:grid-cols-[minmax(0,1fr)_420px] lg:gap-x-16 xl:grid-cols-[minmax(0,1fr)_460px]">

                {{-- Presentación --}}
                <section class="about-hero flex flex-col gap-8 lg:col-start-1" data-code-step="0">
                    <span class="eyebrow">// {{ __('site.about.eyebrow') }}</span>
                    <h1 class="font-display text-5xl leading-[0.92] font-black tracking-tight uppercase sm:text-7xl xl:text-8xl">
                        <span class="about-rise block">{{ __('site.about.title_a') }}</span>
                        <span class="about-rise block text-malbec" style="--rise: 1">{{ __('site.about.title_b') }}</span>
                    </h1>
                    <p class="about-rise max-w-xl text-lg leading-relaxed text-muted sm:text-xl" style="--rise: 2">{{ __('site.about.lead') }}</p>
                    <div class="about-rise flex flex-wrap gap-3" style="--rise: 3">
                        <a href="#contacto" class="btn btn-primary">{{ __('site.about.cta_primary') }} <x-heroicon-m-arrow-right class="size-4" /></a>
                        <a href="{{ $profile['linkedin'] }}" target="_blank" rel="noopener" class="btn btn-ghost"><x-brand-icon name="linkedin" class="size-4" /> LinkedIn</a>
                    </div>
                </section>

            {{-- Editor que se va completando a medida que bajás --}}
            <aside class="-mt-10 sm:-mt-16 lg:col-start-2 lg:row-span-5 lg:row-start-1 lg:mt-0">
                <div class="about-code overflow-hidden rounded-xl border border-line bg-night/90 shadow-[0_40px_120px_-50px_var(--color-malbec)] backdrop-blur lg:sticky lg:top-28" x-ref="editor">
                    <div class="flex items-center gap-2 border-b border-line bg-panel px-4 py-3">
                        <span class="size-2.5 rounded-full bg-[#ff5f57]"></span>
                        <span class="size-2.5 rounded-full bg-[#febc2e]"></span>
                        <span class="size-2.5 rounded-full bg-[#28c840]"></span>
                        <span class="mx-auto font-mono text-[11px] text-dim">{{ $en ? 'about.ts' : 'sobre-mi.ts' }}</span>
                    </div>
                    <div class="overflow-x-hidden py-4 font-mono text-[12px] leading-[1.75] sm:text-[12.5px]">
                        @foreach ($blocks as $step => $lines)
                            <div data-code-block="{{ $step }}">
                                @foreach ($lines as $tokens)
                                    @php($lineNumber++)
                                    <div class="flex">
                                        <span class="w-10 shrink-0 pr-3 text-right text-line select-none">{{ $lineNumber }}</span>
                                        <span class="code-line" style="--chars: {{ mb_strlen(collect($tokens)->pluck(1)->join('')) }}">@foreach ($tokens as [$type, $text])<span class="{{ $colors[$type] }}">{{ $text }}</span>@endforeach</span>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>

                {{-- Historia y formación --}}
                <section class="grid items-start gap-10 sm:grid-cols-[220px_1fr] lg:col-start-1" data-code-step="1">
                    <figure class="relative mx-auto w-full max-w-[260px] sm:mx-0" data-reveal>
                        <div class="absolute -inset-2.5 rotate-[-3deg] rounded-3xl border border-malbec/40"></div>
                        <div class="group relative overflow-hidden rounded-2xl border border-line bg-panel">
                            <img src="{{ asset($profile['photo']) }}" alt="{{ $profile['name'] }}" width="720" height="900" loading="lazy"
                                 class="aspect-[4/5] w-full object-cover grayscale transition duration-700 group-hover:grayscale-0">
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-ink via-transparent to-transparent"></div>
                            <figcaption class="absolute inset-x-0 bottom-0 flex items-center justify-between p-4 font-mono text-[10px] tracking-widest uppercase">
                                <span>// {{ $profile['short_name'] }}</span>
                                <span class="text-malbec">MZA · AR</span>
                            </figcaption>
                        </div>
                    </figure>
                    <div class="flex flex-col gap-6">
                        <x-home.section-heading :eyebrow="__('site.about.story_eyebrow')" :title-a="__('site.about.story_title_a')" :title-b="__('site.about.story_title_b')" />
                        @foreach (__('site.about.story') as $paragraph)
                            <p class="text-lg leading-relaxed text-muted" data-reveal>{{ $paragraph }}</p>
                        @endforeach
                        <dl class="grid gap-px overflow-hidden rounded-xl border border-line bg-line sm:grid-cols-3" data-reveal>
                            <div class="flex flex-col gap-1.5 bg-panel p-5">
                                <dt class="font-mono text-[10px] tracking-[0.2em] text-dim uppercase">{{ __('site.about.experience_label') }}</dt>
                                <dd class="font-display text-2xl text-malbec">{{ $profile['years_experience'] }} {{ __('site.hero.years') }}</dd>
                            </div>
                            <div class="flex flex-col gap-1.5 bg-panel p-5">
                                <dt class="font-mono text-[10px] tracking-[0.2em] text-dim uppercase">{{ __('site.about.gpa_label') }}</dt>
                                <dd class="font-display text-2xl text-malbec">{{ $gpa }} / 10</dd>
                            </div>
                            <div class="flex flex-col gap-1.5 bg-panel p-5">
                                <dt class="font-mono text-[10px] tracking-[0.2em] text-dim uppercase">{{ __('site.about.mode_label') }}</dt>
                                <dd class="font-medium">{{ __('site.about.mode') }}</dd>
                            </div>
                            <div class="flex flex-col gap-1.5 bg-panel p-5 sm:col-span-3">
                                <dt class="font-mono text-[10px] tracking-[0.2em] text-dim uppercase">{{ __('site.about.degree_label') }}</dt>
                                <dd class="font-medium">{{ __('site.about.degree') }}</dd>
                            </div>
                        </dl>
                    </div>
                </section>

                {{-- Principios --}}
                <section class="flex flex-col gap-10 lg:col-start-1" data-code-step="2">
                    <x-home.section-heading :eyebrow="__('site.about.principles_eyebrow')" :title-a="__('site.about.principles_title_a')" :title-b="__('site.about.principles_title_b')" />
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach (__('site.about.principles') as $index => $principle)
                            <article class="spotlight group flex flex-col gap-4 rounded-2xl border border-line bg-panel p-7 transition duration-500 hover:-translate-y-1" data-reveal data-delay="{{ ($index % 2) * 120 }}">
                                <span class="spotlight-border"></span>
                                <span class="font-mono text-xs text-malbec">0{{ $index + 1 }}</span>
                                <h3 class="font-display text-lg font-bold tracking-tight uppercase">{{ $principle['title'] }}</h3>
                                <p class="leading-relaxed text-muted">{{ $principle['text'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                {{-- Proyectos --}}
                @if ($projects->isNotEmpty())
                    <section class="flex flex-col gap-10 lg:col-start-1" data-code-step="3">
                        <x-home.section-heading :eyebrow="__('site.about.work_eyebrow')" :title-a="__('site.about.work_title_a')" :title-b="__('site.about.work_title_b')" :lead="__('site.about.work_lead')" />
                        <ul class="flex flex-col border-t border-line" data-reveal>
                            @foreach ($projects as $project)
                                <li>
                                    <a href="{{ \App\Support\LocalizedRoute::to('project', $project) }}" class="group flex items-center justify-between gap-6 border-b border-line py-5 transition hover:pl-3">
                                        <span class="flex flex-col gap-1">
                                            <span class="font-display text-lg font-bold tracking-tight uppercase transition group-hover:text-malbec sm:text-xl">{{ $project->localized('title') }}</span>
                                            <span class="font-mono text-[11px] tracking-[0.18em] text-dim uppercase">{{ $project->localized('category') }}</span>
                                        </span>
                                        <x-heroicon-m-arrow-up-right class="size-5 shrink-0 text-dim transition group-hover:translate-x-1 group-hover:-translate-y-1 group-hover:text-malbec" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                {{-- Mendoza y disponibilidad --}}
                <section class="relative overflow-hidden rounded-2xl border border-line bg-panel p-8 sm:p-12 lg:col-start-1" data-code-step="4" data-reveal>
                    <div class="pointer-events-none absolute -right-24 -bottom-24 h-72 w-72 rounded-full bg-sun/10 blur-[100px]"></div>
                    <div class="relative flex flex-col gap-5">
                        <span class="eyebrow">// {{ __('site.about.place_eyebrow') }}</span>
                        <h2 class="section-title">{{ __('site.about.place_title_a') }} <span class="text-malbec">{{ __('site.about.place_title_b') }}</span></h2>
                        <p class="max-w-2xl text-lg leading-relaxed text-muted">{{ __('site.about.place_text') }}</p>
                    </div>
                </section>
        </div>
    </div>

    <x-home.stack :profile="$profile" />

    @if ($testimonials->isNotEmpty())
        <x-home.testimonials :testimonials="$testimonials" />
    @endif
</x-layouts.site>
