<x-layouts.site :title="__('site.pages.blog.title')" :description="__('site.pages.blog.description')">
    <section id="blog" class="relative overflow-hidden pt-32 pb-24 sm:pt-40 sm:pb-32">
        <div class="grid-bg pointer-events-none absolute inset-0"></div>
        <div class="pointer-events-none absolute -top-40 right-0 h-[480px] w-[720px] rounded-full bg-malbec/15 blur-[150px]"></div>

        <div class="container-site relative flex flex-col gap-14">
            <div class="flex flex-col gap-5" data-reveal>
                <span class="eyebrow">// {{ __('site.blog.eyebrow') }}</span>
                <h1 class="font-display text-4xl leading-[0.95] font-black tracking-tight uppercase sm:text-6xl lg:text-7xl">
                    {{ __('site.blog.title_a') }} <span class="text-malbec">{{ __('site.blog.title_b') }}</span>
                </h1>
                <p class="max-w-2xl text-lg leading-relaxed text-muted">{{ __('site.blog.lead') }}</p>
            </div>

            @if ($posts->isEmpty())
                <p class="rounded-2xl border border-dashed border-line p-10 text-center text-muted">{{ __('site.blog.empty') }}</p>
            @else
                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $index => $post)
                        <x-post-card :post="$post" :index="$index" :featured="$loop->first" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layouts.site>
