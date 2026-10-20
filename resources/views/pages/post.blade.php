@php
    $profile = config('profile');
    $title = $post->localized('title');
    $whatsappUrl = 'https://wa.me/'.$profile['whatsapp'].'?text='.rawurlencode(__('site.blog.whatsapp_message', ['title' => $title]));
    $article = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $title,
        'description' => $post->localized('excerpt'),
        'datePublished' => $post->published_at->toAtomString(),
        'dateModified' => $post->updated_at->toAtomString(),
        'inLanguage' => app()->getLocale(),
        'mainEntityOfPage' => url()->current(),
        'image' => asset('images/og-andres-padovan.jpg'),
        'author' => ['@type' => 'Person', 'name' => $profile['name'], 'url' => \App\Support\LocalizedRoute::to('about')],
    ];
@endphp

<x-layouts.site :title="$title.' · '.$profile['name']" :description="$post->localized('excerpt')">
    @push('head')
        <script type="application/ld+json">{!! json_encode($article, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endpush

    <article class="relative overflow-hidden pt-32 pb-24 sm:pt-40">
        <div class="grid-bg pointer-events-none absolute inset-x-0 top-0 h-[620px]"></div>
        <div class="pointer-events-none absolute -top-40 left-1/2 h-[480px] w-[820px] -translate-x-1/2 rounded-full bg-malbec/15 blur-[150px]"></div>

        <div class="container-site relative flex flex-col gap-14">
            <header class="mx-auto flex w-full max-w-3xl flex-col gap-7">
                <a href="{{ \App\Support\LocalizedRoute::to('blog') }}" class="inline-flex items-center gap-2 self-start font-mono text-xs tracking-widest text-muted uppercase transition hover:gap-3 hover:text-malbec" data-reveal>
                    <x-heroicon-m-arrow-left class="size-4" /> {{ __('site.blog.back') }}
                </a>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 font-mono text-[11px] tracking-[0.2em] uppercase" data-reveal>
                    <span class="text-malbec">{{ __('site.blog.categories.'.$post->category) }}</span>
                    <time datetime="{{ $post->published_at->toDateString() }}" class="text-dim">{{ $post->published_at->translatedFormat(__('site.blog.date_format')) }}</time>
                    <span class="text-dim">{{ trans_choice('site.blog.minutes', $post->readingMinutes(), ['count' => $post->readingMinutes()]) }}</span>
                </div>
                <h1 class="font-display text-3xl leading-[1.02] font-black tracking-tight uppercase sm:text-5xl" data-reveal data-delay="100">{{ $title }}</h1>
                <p class="text-xl leading-relaxed text-muted" data-reveal data-delay="150">{{ $post->localized('excerpt') }}</p>
                <div class="flex items-center gap-3 border-y border-line py-4" data-reveal data-delay="200">
                    <img src="{{ asset($profile['photo']) }}" alt="" class="size-10 rounded-full object-cover grayscale" width="40" height="40">
                    <div class="flex flex-col">
                        <span class="text-sm font-medium">{{ $profile['name'] }}</span>
                        <span class="font-mono text-[11px] text-dim">{{ __('site.hero.kicker') }}</span>
                    </div>
                </div>
            </header>

            <div class="prose-site mx-auto w-full max-w-3xl" data-reveal>
                {!! $post->bodyHtml() !!}
            </div>

            <aside class="relative mx-auto flex w-full max-w-3xl flex-col items-start gap-5 overflow-hidden rounded-2xl border border-malbec/40 bg-panel p-8 sm:p-10" data-reveal>
                <div class="pointer-events-none absolute -right-20 -bottom-20 h-64 w-64 rounded-full bg-malbec/15 blur-[90px]"></div>
                <span class="eyebrow relative">// {{ __('site.blog.cta_eyebrow') }}</span>
                <h2 class="relative font-display text-2xl font-bold tracking-tight uppercase">{{ __('site.blog.cta_title') }}</h2>
                <p class="relative max-w-xl text-muted">{{ __('site.blog.cta_text') }}</p>
                <div class="relative flex flex-wrap gap-3">
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-primary"><x-brand-icon name="whatsapp" class="size-4" /> {{ __('site.blog.cta_button') }}</a>
                    <a href="{{ \App\Support\LocalizedRoute::to('services') }}" class="btn btn-ghost">{{ __('site.blog.cta_services') }}</a>
                </div>
            </aside>
        </div>
    </article>

    @if ($others->isNotEmpty())
        <section class="border-y border-line/60 bg-night py-24 sm:py-28">
            <div class="container-site flex flex-col gap-12">
                <x-home.section-heading :eyebrow="__('site.blog.eyebrow')" :title-a="__('site.blog.more_a')" :title-b="__('site.blog.more_b')" />
                <div class="grid gap-5 md:grid-cols-2">
                    @foreach ($others as $index => $other)
                        <x-post-card :post="$other" :index="$index" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.site>
