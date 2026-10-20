@php
    $pages = [
        ['route' => 'services', 'icon' => 'squares-2x2', 'key' => 'services'],
        ['route' => 'process', 'icon' => 'map', 'key' => 'process'],
        ['route' => 'about', 'icon' => 'user', 'key' => 'about'],
    ];
@endphp

{{-- Cierre de la portada: las otras secciones, cada una en su página. --}}
<section class="py-24 sm:py-32">
    <div class="container-site flex flex-col gap-12">
        <x-home.section-heading :eyebrow="__('site.explore.eyebrow')" :title-a="__('site.explore.title_a')" :title-b="__('site.explore.title_b')" />

        <div class="grid gap-4 md:grid-cols-3">
            @foreach ($pages as $index => $page)
                <a href="{{ \App\Support\LocalizedRoute::to($page['route']) }}"
                   class="spotlight group flex min-h-[230px] flex-col justify-between gap-10 rounded-2xl border border-line bg-panel p-7 transition duration-500 hover:-translate-y-1 hover:border-malbec/50 sm:p-9"
                   data-reveal data-delay="{{ $index * 120 }}">
                    <span class="spotlight-border"></span>
                    <div class="flex items-start justify-between gap-6">
                        <span class="grid size-12 place-items-center rounded-xl border border-line bg-night text-malbec transition duration-500 group-hover:border-malbec/60 group-hover:shadow-[0_0_30px_-6px_rgba(255,61,127,0.6)]">
                            <x-dynamic-component :component="'heroicon-o-'.$page['icon']" class="size-6" />
                        </span>
                        <x-heroicon-m-arrow-up-right class="size-5 text-dim transition duration-300 group-hover:translate-x-1 group-hover:-translate-y-1 group-hover:text-malbec" />
                    </div>
                    <div class="flex flex-col gap-3">
                        <h3 class="font-display text-xl font-bold tracking-tight uppercase">{{ __('site.nav.'.$page['key']) }}</h3>
                        <p class="leading-relaxed text-muted">{{ __('site.explore.items.'.$page['key']) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
