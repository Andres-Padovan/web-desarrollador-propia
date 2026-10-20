@props(['projects', 'categories', 'profile'])

<section id="proyectos" class="relative border-y border-line/60 bg-night py-24 sm:py-32" x-data="{ filter: 'all' }">
    <div class="container-site flex flex-col gap-12">
        <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
            <x-home.section-heading :eyebrow="__('site.projects.eyebrow')" :title-a="__('site.projects.title_a')" :title-b="__('site.projects.title_b')" :lead="__('site.projects.lead')" />

            @if ($categories->count() > 1)
                <div class="flex flex-wrap gap-2" role="tablist" data-reveal>
                    <button type="button" class="chip cursor-pointer transition" :class="filter === 'all' && '!border-malbec !text-malbec'" @click="filter = 'all'">{{ __('site.projects.all') }}</button>
                    @foreach ($categories as $category)
                        <button type="button" class="chip cursor-pointer transition" :class="filter === @js($category) && '!border-malbec !text-malbec'" @click="filter = @js($category)">{{ $category }}</button>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($projects->isEmpty())
            <div class="flex flex-col items-start gap-5 rounded-2xl border border-dashed border-line p-10" data-reveal>
                <p class="font-mono text-sm text-malbec">$ git log --oneline | head</p>
                <h3 class="font-display text-xl uppercase">{{ __('site.projects.empty_title') }}</h3>
                <p class="max-w-xl text-muted">{{ __('site.projects.empty_text') }}</p>
                <a href="{{ $profile['github'] }}" target="_blank" rel="noopener" class="btn btn-ghost"><x-brand-icon name="github" class="size-4" /> {{ __('site.projects.github') }}</a>
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $index => $project)
                    @php
                        $accent = $project->accent ?: '#ff3d7f';
                        $url = $project->primaryUrl();
                    @endphp
                    <article class="spotlight group flex flex-col overflow-hidden rounded-2xl border border-line bg-panel transition duration-500 hover:-translate-y-1.5"
                             @if ($project->videoUrl()) x-data="projectVideo" @mouseenter="play()" @mouseleave="pause()" @endif
                             x-show="filter === 'all' || filter === @js($project->localized('category'))"
                             x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0 scale-95"
                             data-reveal data-delay="{{ ($index % 3) * 120 }}">
                        <span class="spotlight-border"></span>

                        <div class="relative aspect-[16/10] overflow-hidden border-b border-line bg-ink">
                            @if ($project->imageUrl())
                                <img src="{{ $project->imageUrl() }}" alt="{{ $project->localized('title') }}" loading="lazy"
                                     class="size-full object-cover object-top transition duration-700 group-hover:scale-105">
                            @endif
                            @if ($project->videoUrl())
                                {{-- Recorrido del sitio: se reproduce al pasar el mouse (o al verse en pantalla en celulares) --}}
                                <video x-ref="video" @if ($project->imageUrl()) poster="{{ $project->imageUrl() }}" @endif
                                       muted loop playsinline preload="none" aria-hidden="true"
                                       class="absolute inset-0 z-10 size-full object-cover object-top opacity-0 transition-opacity duration-500"
                                       :class="playing && 'opacity-100'">
                                    @foreach ($project->videoSources() as $source => $type)
                                        <source src="{{ $source }}" type="{{ $type }}">
                                    @endforeach
                                </video>
                                <span class="absolute bottom-3 left-3 z-10 inline-flex items-center gap-1.5 rounded-sm border border-line bg-ink/80 px-2 py-1 font-mono text-[10px] tracking-widest text-fg uppercase backdrop-blur transition duration-300"
                                      :class="playing && 'opacity-0'">
                                    <x-heroicon-m-play class="size-3 text-malbec" /> {{ __('site.projects.video') }}
                                </span>
                            @endif
                            @if (! $project->imageUrl())
                                {{-- Portada generada: ventana de editor con el nombre del proyecto --}}
                                <div class="absolute inset-0 opacity-70" style="background: radial-gradient(circle at 80% 0%, {{ $accent }}44, transparent 55%)"></div>
                                <div class="absolute inset-x-6 top-6 bottom-0 rounded-t-lg border border-b-0 border-line bg-night/90 p-4 font-mono text-[11px] leading-relaxed transition duration-700 group-hover:translate-y-[-6px]">
                                    <div class="mb-3 flex gap-1.5"><span class="size-2 rounded-full bg-line"></span><span class="size-2 rounded-full bg-line"></span><span class="size-2 rounded-full bg-line"></span></div>
                                    <p class="text-dim">// {{ $project->language ?: 'project' }}</p>
                                    <p><span style="color: {{ $accent }}">const</span> <span class="text-fg">{{ \Illuminate\Support\Str::camel($project->slug) }}</span> = {</p>
                                    <p class="pl-4 text-muted">status: <span class="text-sun">'{{ $project->status }}'</span>,</p>
                                    <p class="pl-4 text-muted">stack: [{{ collect($project->stack)->take(3)->map(fn ($tech) => "'".$tech."'")->join(', ') }}],</p>
                                    <p>}</p>
                                    <p class="mt-4 font-display text-2xl font-black tracking-tight uppercase" style="color: {{ $accent }}">{{ $project->localized('title') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col gap-4 p-6">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-mono text-[10px] tracking-[0.2em] text-malbec uppercase">{{ $project->localized('category') ?: $project->language }}</span>
                                <span class="inline-flex items-center gap-1.5 rounded-sm border border-line px-2 py-0.5 font-mono text-[10px] tracking-widest uppercase {{ $project->status === 'live' ? 'text-leaf' : 'text-sun' }}">
                                    <span class="size-1.5 rounded-full bg-current"></span>{{ __('site.projects.status.'.$project->status) }}
                                </span>
                            </div>
                            <h3 class="font-display text-xl font-bold uppercase">{{ $project->localized('title') }}</h3>
                            <p class="flex-1 leading-relaxed text-muted">{{ $project->localized('summary') }}</p>
                            @if ($project->stack)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($project->stack as $tech)
                                        <span class="chip">{{ $tech }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="flex items-center gap-4 border-t border-line pt-4 font-mono text-xs">
                                @if ($project->live_url)
                                    <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-fg transition hover:text-malbec">{{ __('site.projects.view') }} <x-heroicon-m-arrow-up-right class="size-3.5" /></a>
                                @endif
                                @if ($project->repo_url && ! $project->is_private)
                                    <a href="{{ $project->repo_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-muted transition hover:text-malbec"><x-brand-icon name="github" class="size-3.5" /> {{ __('site.projects.code') }}</a>
                                @elseif ($project->is_private)
                                    <span class="inline-flex items-center gap-1.5 text-dim"><x-heroicon-m-lock-closed class="size-3.5" /> {{ __('site.projects.private') }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach

                {{-- Siempre cierra la grilla con una invitación: la próxima tarjeta puede ser la del visitante --}}
                <a href="#contacto" x-show="filter === 'all'" data-reveal data-delay="{{ ($projects->count() % 3) * 120 }}"
                   class="spotlight group flex min-h-[320px] flex-col justify-between gap-8 rounded-2xl border border-dashed border-malbec/40 bg-gradient-to-br from-malbec-deep/30 to-transparent p-7 transition duration-500 hover:-translate-y-1.5 hover:border-malbec">
                    <span class="spotlight-border"></span>
                    <span class="font-mono text-sm text-malbec">$ git checkout -b tu-proyecto<span class="animate-blink">▋</span></span>
                    <span class="flex flex-col gap-3">
                        <span class="font-display text-2xl leading-tight font-bold uppercase">{{ __('site.projects.next_title') }}</span>
                        <span class="text-muted">{{ __('site.projects.next_text') }}</span>
                    </span>
                    <span class="inline-flex items-center gap-2 font-mono text-xs tracking-widest text-fg uppercase transition group-hover:gap-4 group-hover:text-malbec">{{ __('site.nav.contact') }} <x-heroicon-m-arrow-right class="size-4" /></span>
                </a>
            </div>
        @endif
    </div>
</section>
