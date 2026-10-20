@php
    $title = $project->localized('title');
    $accent = $project->accent ?: 'var(--color-malbec)';
@endphp

<x-layouts.site :title="$title.' · '.config('profile.name')" :description="$project->localized('summary')">
    <section class="relative overflow-hidden pt-32 pb-20 sm:pt-40 sm:pb-28">
        <div class="grid-bg pointer-events-none absolute inset-0"></div>
        <div class="pointer-events-none absolute -top-40 right-0 h-[520px] w-[720px] rounded-full blur-[150px]" style="background: color-mix(in oklab, {{ $accent }} 20%, transparent)"></div>

        <div class="container-site relative flex flex-col gap-12">
            <a href="{{ \App\Support\LocalizedRoute::to('projects') }}" class="inline-flex items-center gap-2 self-start font-mono text-xs tracking-widest text-muted uppercase transition hover:gap-3 hover:text-malbec" data-reveal>
                <x-heroicon-m-arrow-left class="size-4" /> {{ __('site.projects.back') }}
            </a>

            <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                <div class="flex flex-col gap-7">
                    <div class="flex flex-wrap items-center gap-3" data-reveal>
                        <span class="font-mono text-xs tracking-[0.2em] text-malbec uppercase">{{ $project->localized('category') ?: $project->language }}</span>
                        <x-project-status :status="$project->status" />
                    </div>
                    <h1 class="font-display text-4xl leading-[0.95] font-black tracking-tight uppercase sm:text-6xl" data-reveal data-delay="100">{{ $title }}</h1>
                    <p class="max-w-xl text-lg leading-relaxed text-muted" data-reveal data-delay="150">{{ $project->localized('summary') }}</p>

                    @if ($project->stack)
                        <div class="flex flex-col gap-3" data-reveal data-delay="200">
                            <span class="font-mono text-[10px] tracking-[0.2em] text-dim uppercase">{{ __('site.projects.stack_label') }}</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($project->stack as $tech)
                                    <span class="chip">{{ $tech }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center gap-3" data-reveal data-delay="250">
                        <a href="#contacto" class="btn btn-primary">{{ __('site.projects.want_one') }} <x-heroicon-m-arrow-right class="size-4" /></a>
                        @if ($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn btn-ghost">{{ __('site.projects.view') }} <x-heroicon-m-arrow-up-right class="size-4" /></a>
                        @endif
                        @if ($project->repo_url && ! $project->is_private)
                            <a href="{{ $project->repo_url }}" target="_blank" rel="noopener" class="btn btn-ghost"><x-brand-icon name="github" class="size-4" /> {{ __('site.projects.code') }}</a>
                        @elseif ($project->is_private)
                            <span class="inline-flex items-center gap-1.5 font-mono text-xs text-dim"><x-heroicon-m-lock-closed class="size-3.5" /> {{ __('site.projects.private_note') }}</span>
                        @endif
                    </div>
                </div>

                {{-- El recorrido en grande, en una ventana de navegador --}}
                <div class="overflow-hidden rounded-xl border border-line bg-night shadow-[0_40px_120px_-40px_rgba(0,0,0,0.9)]" data-reveal data-delay="200"
                     style="box-shadow: 0 40px 120px -40px color-mix(in oklab, {{ $accent }} 40%, transparent)">
                    <div class="flex items-center gap-2 border-b border-line bg-panel px-4 py-3">
                        <span class="size-2.5 rounded-full bg-[#ff5f57]"></span>
                        <span class="size-2.5 rounded-full bg-[#febc2e]"></span>
                        <span class="size-2.5 rounded-full bg-[#28c840]"></span>
                        <span class="mx-auto truncate font-mono text-[11px] text-dim">{{ $project->live_url ? parse_url($project->live_url, PHP_URL_HOST) : $project->slug }}</span>
                    </div>
                    <div class="group relative aspect-[16/10] bg-ink">
                        @if ($project->videoUrl())
                            <video autoplay muted loop playsinline preload="metadata" @if ($project->imageUrl()) poster="{{ $project->imageUrl() }}" @endif
                                   class="size-full object-cover object-top" aria-label="{{ __('site.projects.video') }}">
                                @foreach ($project->videoSources() as $source => $type)
                                    <source src="{{ $source }}" type="{{ $type }}">
                                @endforeach
                            </video>
                        @elseif ($project->imageUrl())
                            <img src="{{ $project->imageUrl() }}" alt="{{ $title }}" class="size-full object-cover object-top">
                        @else
                            <x-project-cover :project="$project" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($others->isNotEmpty())
        <section class="border-y border-line/60 bg-night py-24 sm:py-28">
            <div class="container-site flex flex-col gap-12">
                <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                    <x-home.section-heading :eyebrow="__('site.projects.eyebrow')" :title-a="__('site.projects.more_a')" :title-b="__('site.projects.more_b')" />
                    <a href="{{ \App\Support\LocalizedRoute::to('projects') }}" class="btn btn-ghost shrink-0 self-start sm:self-auto" data-reveal>{{ __('site.projects.all_projects') }} <x-heroicon-m-arrow-right class="size-4" /></a>
                </div>
                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($others as $index => $other)
                        <x-project-card :project="$other" :index="$index" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.site>
