@php
    $profile = config('profile');
    $whatsappFor = fn (string $package) => 'https://wa.me/'.$profile['whatsapp'].'?text='.rawurlencode(__('site.services.want_message', ['package' => $package]));
    $number = 0;
@endphp

<x-layouts.site :title="__('site.pages.services.title')" :description="__('site.pages.services.description')">
    <section class="relative overflow-hidden pt-32 pb-24 sm:pt-40 sm:pb-32"
             x-data="{ tab: @js($groups->keys()->first()), groups: @js($groups->keys()) }"
             x-init="const hash = location.hash.slice(1); if (groups.includes(hash)) tab = hash">
        <div class="grid-bg pointer-events-none absolute inset-0"></div>
        <div class="pointer-events-none absolute -top-40 left-1/2 h-[480px] w-[820px] -translate-x-1/2 rounded-full bg-malbec/15 blur-[140px]"></div>

        <div class="container-site relative flex flex-col gap-14">
            <div class="flex flex-col gap-5" data-reveal>
                <span class="eyebrow">// {{ __('site.services.eyebrow') }}</span>
                <h1 class="font-display text-4xl leading-[0.95] font-black tracking-tight uppercase sm:text-6xl lg:text-7xl">
                    {{ __('site.services.title_a') }} <span class="text-malbec">{{ __('site.services.title_b') }}</span>
                </h1>
                <p class="max-w-2xl text-lg leading-relaxed text-muted">{{ __('site.services.lead') }}</p>
            </div>

            {{-- Pestañas por tipo de trabajo --}}
            <div class="-mx-4 overflow-x-auto px-4 pb-1 sm:mx-0 sm:px-0" data-reveal data-delay="100">
                <div class="flex w-max gap-2 rounded-full border border-line bg-night/70 p-1.5 backdrop-blur" role="tablist">
                    @foreach ($groups as $group => $services)
                        <button type="button" role="tab" @click="tab = @js($group); history.replaceState(null, '', '#{{ $group }}')"
                                :aria-selected="tab === @js($group)"
                                class="flex items-center gap-2 rounded-full px-4 py-2.5 font-mono text-xs tracking-wide whitespace-nowrap transition sm:px-5 sm:text-[13px]"
                                :class="tab === @js($group) ? 'bg-malbec text-ink shadow-[0_10px_30px_-10px_var(--color-malbec)]' : 'text-muted hover:text-fg'">
                            {{ __('site.services.groups.'.$group.'.label') }}
                            <span class="rounded-full px-1.5 text-[10px]" :class="tab === @js($group) ? 'bg-ink/20' : 'bg-line'">{{ $services->count() }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            @foreach ($groups as $group => $services)
                <div x-show="tab === @js($group)" @if (! $loop->first) x-cloak @endif role="tabpanel" class="flex flex-col gap-8"
                     x-transition:enter="transition duration-500 ease-out" x-transition:enter-start="opacity-0 translate-y-4">
                    <p class="max-w-2xl font-mono text-sm text-dim">→ {{ __('site.services.groups.'.$group.'.lead') }}</p>

                    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($services as $service)
                            @php($number++)
                            <article x-data="{ open: false }" @class([
                                'spotlight group relative flex flex-col gap-7 rounded-2xl border bg-panel p-7 transition duration-500 hover:-translate-y-1 sm:p-8',
                                'border-malbec/50 shadow-[0_30px_80px_-40px_var(--color-malbec)]' => $service->is_highlighted,
                                'border-line' => ! $service->is_highlighted,
                            ])>
                                <span class="spotlight-border"></span>

                                <div class="flex items-start justify-between gap-4">
                                    <span class="grid size-12 place-items-center rounded-xl border border-line bg-night text-malbec transition duration-500 group-hover:border-malbec/60">
                                        <x-dynamic-component :component="'heroicon-o-'.($service->icon ?: 'code-bracket')" class="size-6" />
                                    </span>
                                    <div class="flex flex-col items-end gap-2">
                                        <span class="font-mono text-xs text-dim">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>
                                        @if ($service->is_highlighted)
                                            <span class="rounded-full bg-malbec/15 px-2.5 py-1 font-mono text-[10px] tracking-widest text-malbec uppercase">{{ __('site.services.highlighted') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <h2 class="font-display text-xl font-bold tracking-tight uppercase">{{ $service->localized('title') }}</h2>
                                    <p class="leading-relaxed text-muted">{{ $service->localized('description') }}</p>
                                </div>

                                @if ($includes = $service->localized('includes'))
                                    <div class="flex flex-col gap-3">
                                        <span class="font-mono text-[10px] tracking-[0.2em] text-dim uppercase">{{ __('site.services.includes') }}</span>
                                        <ul class="flex flex-col gap-2.5">
                                            @foreach ($includes as $item)
                                                <li class="flex gap-2.5 text-[15px] leading-snug"><x-heroicon-m-check class="mt-0.5 size-4 shrink-0 text-malbec" /> {{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div x-show="open" x-collapse x-cloak class="flex flex-col gap-4">
                                    @if ($service->localized('details'))
                                        <p class="leading-relaxed text-muted">{{ $service->localized('details') }}</p>
                                    @endif
                                    @if ($service->stack)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($service->stack as $tech)
                                                <span class="chip">{{ $tech }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-auto flex flex-col gap-5 border-t border-line pt-5">
                                    <div class="flex items-center justify-between gap-3">
                                        @if ($service->localized('delivery'))
                                            <span class="inline-flex items-center gap-2 text-sm"><x-heroicon-o-clock class="size-4 text-sun" /> <span class="text-dim">{{ __('site.services.delivery') }}</span> {{ $service->localized('delivery') }}</span>
                                        @endif
                                        <button type="button" @click="open = !open" :aria-expanded="open"
                                                class="ml-auto inline-flex items-center gap-1 font-mono text-xs text-muted transition hover:text-malbec">
                                            <span x-text="open ? @js(__('site.services.hide_details')) : @js(__('site.services.details'))">{{ __('site.services.details') }}</span>
                                            <x-heroicon-m-chevron-down class="size-3.5 transition" ::class="open && 'rotate-180'" />
                                        </button>
                                    </div>
                                    <a href="{{ $whatsappFor($service->localized('title')) }}" target="_blank" rel="noopener"
                                       @class(['btn w-full justify-center', 'btn-primary' => $service->is_highlighted, 'btn-ghost' => ! $service->is_highlighted])>
                                        {{ __('site.services.want') }} <x-heroicon-m-arrow-right class="size-4" />
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Lo que viene con cualquier paquete --}}
            <div class="grid gap-px overflow-hidden rounded-2xl border border-line bg-line sm:grid-cols-2 lg:grid-cols-4" data-reveal>
                @foreach (__('site.services.promises') as $promise)
                    <div class="flex flex-col gap-2 bg-night p-6">
                        <x-heroicon-o-check-badge class="size-6 text-malbec" />
                        <h3 class="font-display text-sm font-bold tracking-tight uppercase">{{ $promise['title'] }}</h3>
                        <p class="text-sm leading-relaxed text-muted">{{ $promise['text'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="relative flex flex-col items-start justify-between gap-6 overflow-hidden rounded-2xl border border-line bg-panel p-8 sm:flex-row sm:items-center sm:p-10" data-reveal>
                <div class="pointer-events-none absolute -right-20 -bottom-24 h-64 w-64 rounded-full bg-sun/10 blur-[90px]"></div>
                <div class="relative flex flex-col gap-2">
                    <h2 class="font-display text-2xl font-bold tracking-tight uppercase">{{ __('site.services.custom_title') }}</h2>
                    <p class="max-w-xl text-muted">{{ __('site.services.custom_text') }}</p>
                </div>
                <a href="#contacto" class="btn btn-primary relative shrink-0">{{ __('site.services.custom_cta') }} <x-heroicon-m-arrow-right class="size-4" /></a>
            </div>
        </div>
    </section>

    {{-- Preguntas frecuentes --}}
    <section class="border-y border-line/60 bg-night py-24 sm:py-28">
        <div class="container-site grid gap-12 lg:grid-cols-[0.8fr_1.2fr]">
            <x-home.section-heading :eyebrow="__('site.services.faq_eyebrow')" :title-a="__('site.services.faq_title_a')" :title-b="__('site.services.faq_title_b')" />
            <div class="flex flex-col divide-y divide-line border-y border-line" data-reveal>
                @foreach (__('site.services.faq') as $question)
                    <details class="group py-5">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-6 font-display text-lg font-bold tracking-tight [&::-webkit-details-marker]:hidden">
                            {{ $question['q'] }}
                            <x-heroicon-m-plus class="size-5 shrink-0 text-malbec transition duration-300 group-open:rotate-45" />
                        </summary>
                        <p class="pt-4 leading-relaxed text-muted">{{ $question['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.site>
