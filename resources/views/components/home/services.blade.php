@props(['services'])

<section id="servicios" class="py-24 sm:py-32">
    <div class="container-site flex flex-col gap-14">
        <x-home.section-heading :eyebrow="__('site.services.eyebrow')" :title-a="__('site.services.title_a')" :title-b="__('site.services.title_b')" :lead="__('site.services.lead')" />

        <div class="grid gap-4 md:grid-cols-3">
            @foreach ($services as $index => $service)
                <article @class([
                    'spotlight group flex min-h-[260px] flex-col justify-between gap-10 rounded-2xl border border-line bg-panel p-7 transition duration-500 hover:-translate-y-1 sm:p-9',
                    'md:col-span-2' => $service->span >= 2,
                    'md:col-span-3' => $service->span >= 3,
                ]) data-reveal data-delay="{{ ($index % 3) * 120 }}">
                    <span class="spotlight-border"></span>
                    <div class="flex items-start justify-between gap-6">
                        <span class="grid size-12 place-items-center rounded-xl border border-line bg-night text-malbec transition duration-500 group-hover:border-malbec/60 group-hover:shadow-[0_0_30px_-6px_rgba(255,61,127,0.6)]">
                            <x-dynamic-component :component="'heroicon-o-'.($service->icon ?: 'code-bracket')" class="size-6" />
                        </span>
                        <span class="font-mono text-xs text-dim">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex flex-col gap-4">
                        <h3 class="font-display text-lg font-bold tracking-tight uppercase sm:text-xl">{{ $service->localized('title') }}</h3>
                        <p class="max-w-xl leading-relaxed text-muted">{{ $service->localized('description') }}</p>
                        @if ($service->stack)
                            <div class="flex flex-wrap gap-2 pt-2">
                                @foreach ($service->stack as $tech)
                                    <span class="chip">{{ $tech }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
