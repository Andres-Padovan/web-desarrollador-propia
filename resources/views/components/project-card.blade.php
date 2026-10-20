@props(['project', 'index' => 0, 'filterable' => false])

@php
    $accent = $project->accent ?: 'var(--color-malbec)';
    $detailUrl = \App\Support\LocalizedRoute::to('project', $project);
@endphp

{{-- Tarjeta de proyecto: toda la tarjeta lleva a su página; los links del pie quedan por encima. --}}
<article class="spotlight group relative flex flex-col overflow-hidden rounded-2xl border border-line bg-panel transition duration-500 hover:-translate-y-1.5"
         @if ($project->videoUrl()) x-data="projectVideo" @mouseenter="play()" @mouseleave="pause()" @endif
         @if ($filterable) x-show="filter === 'all' || filter === @js($project->localized('category'))" @endif
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
            <x-project-cover :project="$project" />
        @endif
    </div>

    <div class="flex flex-1 flex-col gap-4 p-6">
        <div class="flex items-center justify-between gap-3">
            <span class="font-mono text-[10px] tracking-[0.2em] text-malbec uppercase">{{ $project->localized('category') ?: $project->language }}</span>
            <x-project-status :status="$project->status" />
        </div>
        <h3 class="font-display text-xl font-bold uppercase">
            <a href="{{ $detailUrl }}" class="transition after:absolute after:inset-0 after:z-20 group-hover:text-malbec">{{ $project->localized('title') }}</a>
        </h3>
        <p class="flex-1 leading-relaxed text-muted">{{ $project->localized('summary') }}</p>
        @if ($project->stack)
            <div class="flex flex-wrap gap-2">
                @foreach ($project->stack as $tech)
                    <span class="chip">{{ $tech }}</span>
                @endforeach
            </div>
        @endif
        <div class="relative z-30 flex items-center gap-4 border-t border-line pt-4 font-mono text-xs">
            <a href="{{ $detailUrl }}" class="inline-flex items-center gap-1.5 text-fg transition hover:gap-2.5 hover:text-malbec">{{ __('site.projects.details') }} <x-heroicon-m-arrow-right class="size-3.5" /></a>
            @if ($project->live_url)
                <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-muted transition hover:text-malbec">{{ __('site.projects.view') }} <x-heroicon-m-arrow-up-right class="size-3.5" /></a>
            @endif
            @if ($project->repo_url && ! $project->is_private)
                <a href="{{ $project->repo_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-muted transition hover:text-malbec"><x-brand-icon name="github" class="size-3.5" /> {{ __('site.projects.code') }}</a>
            @elseif ($project->is_private)
                <span class="ml-auto inline-flex items-center gap-1.5 text-dim"><x-heroicon-m-lock-closed class="size-3.5" /> {{ __('site.projects.private') }}</span>
            @endif
        </div>
    </div>
</article>
