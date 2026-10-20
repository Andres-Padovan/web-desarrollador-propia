@props(['profile'])

<section id="sobre-mi" class="relative overflow-hidden py-24 sm:py-32">
    <div class="pointer-events-none absolute -bottom-40 -left-40 h-[460px] w-[460px] rounded-full bg-malbec/15 blur-[140px]"></div>

    <div class="container-site relative grid items-center gap-14 lg:grid-cols-[0.85fr_1.15fr]">
        <figure class="relative mx-auto w-full max-w-sm" data-reveal>
            <div class="absolute -inset-3 rotate-[-3deg] rounded-3xl border border-malbec/40"></div>
            <div class="group relative overflow-hidden rounded-2xl border border-line bg-panel">
                <img src="{{ asset($profile['photo']) }}" alt="{{ $profile['name'] }}" width="720" height="900" loading="lazy"
                     class="aspect-[4/5] w-full object-cover grayscale transition duration-700 group-hover:grayscale-0">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-ink via-transparent to-transparent"></div>
                <div class="pointer-events-none absolute inset-0 opacity-30 mix-blend-overlay" style="background: repeating-linear-gradient(0deg, transparent 0 3px, rgba(0,0,0,.5) 3px 4px)"></div>
                <figcaption class="absolute inset-x-0 bottom-0 flex items-center justify-between p-5 font-mono text-[11px] tracking-widest uppercase">
                    <span>// {{ $profile['name'] }}</span>
                    <span class="text-malbec">MZA · AR</span>
                </figcaption>
            </div>
        </figure>

        <div class="flex flex-col gap-8">
            <x-home.section-heading :eyebrow="__('site.about.eyebrow')" :title-a="__('site.about.title_a')" :title-b="__('site.about.title_b')" />
            <p class="max-w-2xl text-lg leading-relaxed text-muted" data-reveal>{{ __('site.about.text') }}</p>

            <dl class="grid gap-px overflow-hidden rounded-xl border border-line bg-line sm:grid-cols-2" data-reveal>
                <div class="flex flex-col gap-1.5 bg-panel p-5 sm:col-span-2">
                    <dt class="font-mono text-[10px] tracking-[0.2em] text-dim uppercase">{{ __('site.about.degree_label') }}</dt>
                    <dd class="font-medium">{{ __('site.about.degree') }}</dd>
                </div>
                <div class="flex flex-col gap-1.5 bg-panel p-5">
                    <dt class="font-mono text-[10px] tracking-[0.2em] text-dim uppercase">{{ __('site.about.gpa_label') }}</dt>
                    <dd class="font-display text-2xl text-malbec">{{ app()->getLocale() === 'en' ? str_replace(',', '.', $profile['gpa']).' / 10' : $profile['gpa'].' / 10' }}</dd>
                </div>
                <div class="flex flex-col gap-1.5 bg-panel p-5">
                    <dt class="font-mono text-[10px] tracking-[0.2em] text-dim uppercase">{{ __('site.about.based_label') }}</dt>
                    <dd class="font-medium">{{ $profile['location'] }}</dd>
                </div>
                <div class="flex flex-col gap-1.5 bg-panel p-5 sm:col-span-2">
                    <dt class="font-mono text-[10px] tracking-[0.2em] text-dim uppercase">{{ __('site.about.mode_label') }}</dt>
                    <dd class="font-medium">{{ __('site.about.mode') }}</dd>
                </div>
            </dl>

            <div data-reveal>
                <a href="{{ $profile['linkedin'] }}" target="_blank" rel="noopener" class="btn btn-ghost"><x-brand-icon name="linkedin" class="size-4" /> {{ __('site.about.cta') }}</a>
            </div>
        </div>
    </div>
</section>
