@props(['projects', 'profile', 'categories' => collect(), 'featured' => false, 'total' => null])

@php
    $allProjectsUrl = \App\Support\LocalizedRoute::to('projects');
    $total ??= $projects->count();
@endphp

{{-- En la portada muestra los destacados con un link a /proyectos; en /proyectos, todos con filtros. --}}
<section id="proyectos" class="relative border-y border-line/60 bg-night py-24 sm:py-32" x-data="{ filter: 'all' }">
    <div class="container-site flex flex-col gap-12">
        <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
            <x-home.section-heading :eyebrow="__('site.projects.eyebrow')" :title-a="__('site.projects.title_a')" :title-b="__('site.projects.title_b')" :lead="__('site.projects.lead')" />

            @if ($featured && $total > $projects->count())
                <a href="{{ $allProjectsUrl }}" class="btn btn-ghost shrink-0 max-lg:hidden" data-reveal>{{ __('site.projects.view_all', ['count' => $total]) }} <x-heroicon-m-arrow-right class="size-4" /></a>
            @elseif (! $featured && $categories->count() > 1)
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
                    <x-project-card :project="$project" :index="$index" :filterable="! $featured" />
                @endforeach

                @unless ($featured)
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
                @endunless
            </div>

            @if ($featured && $total > $projects->count())
                <a href="{{ $allProjectsUrl }}" class="btn btn-primary self-start lg:hidden" data-reveal>{{ __('site.projects.view_all', ['count' => $total]) }} <x-heroicon-m-arrow-right class="size-4" /></a>
            @endif
        @endif
    </div>
</section>
