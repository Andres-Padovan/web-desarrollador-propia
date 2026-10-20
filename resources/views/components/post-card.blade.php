@props(['post', 'index' => 0, 'featured' => false])

@php($url = \App\Support\LocalizedRoute::to('post', $post))

{{-- Tarjeta de artículo: toda la tarjeta lleva al artículo. --}}
<article @class([
    'spotlight group relative flex flex-col overflow-hidden rounded-2xl border border-line bg-panel transition duration-500 hover:-translate-y-1.5',
    'lg:col-span-2 lg:grid lg:grid-cols-[1.1fr_1fr]' => $featured,
]) data-reveal data-delay="{{ ($index % 3) * 120 }}">
    <span class="spotlight-border"></span>

    <div @class(['relative hidden overflow-hidden border-b border-line bg-ink sm:block', 'aspect-[16/9]' => ! $featured, 'aspect-[16/9] lg:aspect-auto lg:border-r lg:border-b-0' => $featured])>
        <div class="grid-bg absolute inset-0 opacity-60"></div>
        <div class="absolute inset-0" style="background: radial-gradient(circle at {{ ['20% 10%', '80% 0%', '50% 100%'][$index % 3] }}, color-mix(in oklab, var(--color-malbec) 28%, transparent), transparent 60%)"></div>
        <div class="absolute inset-x-6 top-6 bottom-0 rounded-t-lg border border-b-0 border-line bg-night/90 p-4 font-mono text-[11px] leading-relaxed transition duration-700 group-hover:-translate-y-1.5">
            <div class="mb-3 flex gap-1.5"><span class="size-2 rounded-full bg-line"></span><span class="size-2 rounded-full bg-line"></span><span class="size-2 rounded-full bg-line"></span></div>
            <p class="text-dim">~/blog/{{ $post->slug }}.md</p>
            <p class="mt-3 text-malbec"># <span class="text-fg">{{ \Illuminate\Support\Str::limit($post->localized('title'), 60) }}</span></p>
            <p class="mt-2 text-muted">{{ \Illuminate\Support\Str::limit($post->localized('excerpt'), 90) }}</p>
        </div>
    </div>

    <div class="flex flex-1 flex-col gap-4 p-6 sm:p-7">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 font-mono text-[10px] tracking-[0.2em] uppercase">
            <span class="text-malbec">{{ __('site.blog.categories.'.$post->category) }}</span>
            <span class="text-dim">{{ $post->published_at->translatedFormat(__('site.blog.date_format')) }}</span>
            <span class="text-dim">{{ trans_choice('site.blog.minutes', $post->readingMinutes(), ['count' => $post->readingMinutes()]) }}</span>
        </div>
        <h2 @class(['font-display font-bold tracking-tight break-words uppercase', 'text-xl sm:text-3xl' => $featured, 'text-lg sm:text-xl' => ! $featured])>
            <a href="{{ $url }}" class="transition after:absolute after:inset-0 after:z-20 group-hover:text-malbec">{{ $post->localized('title') }}</a>
        </h2>
        <p class="flex-1 leading-relaxed text-muted">{{ $post->localized('excerpt') }}</p>
        <span class="inline-flex items-center gap-1.5 border-t border-line pt-4 font-mono text-xs text-fg transition group-hover:gap-2.5 group-hover:text-malbec">{{ __('site.blog.read') }} <x-heroicon-m-arrow-right class="size-3.5" /></span>
    </div>
</article>
