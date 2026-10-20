@props(['testimonials'])

<section id="resenas" class="border-y border-line/60 bg-night py-24 sm:py-32">
    <div class="container-site flex flex-col gap-12">
        <x-home.section-heading :eyebrow="__('site.testimonials.eyebrow')" :title-a="__('site.testimonials.title_a')" :title-b="__('site.testimonials.title_b')" />

        <div class="overflow-x-auto" data-reveal>
            <table class="w-full min-w-[720px] text-left">
                <thead>
                    <tr class="border-b border-line font-mono text-[10px] tracking-[0.2em] text-dim uppercase">
                        <th class="py-3 pr-4 font-medium">#</th>
                        <th class="py-3 pr-4 font-medium">{{ __('site.testimonials.client') }}</th>
                        <th class="py-3 pr-4 font-medium">{{ __('site.testimonials.project') }}</th>
                        <th class="py-3 pr-4 font-medium">{{ __('site.testimonials.rating') }}</th>
                        <th class="py-3 font-medium">{{ __('site.testimonials.quote') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($testimonials as $index => $testimonial)
                        <tr class="border-b border-line/70 transition hover:bg-panel/60">
                            <td class="py-5 pr-4 font-display text-lg text-malbec">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-5 pr-4 font-mono text-sm tracking-wider uppercase">{{ $testimonial->name }}@if ($testimonial->company)<span class="block text-[11px] text-dim normal-case">{{ $testimonial->company }}</span>@endif</td>
                            <td class="py-5 pr-4 font-mono text-[11px] tracking-[0.18em] text-muted uppercase">{{ $testimonial->localized('project_type') }}</td>
                            <td class="py-5 pr-4 text-sun" aria-label="{{ $testimonial->rating }}/5">{{ str_repeat('★', $testimonial->rating) }}<span class="text-line">{{ str_repeat('★', 5 - $testimonial->rating) }}</span></td>
                            <td class="py-5 text-fg/90 italic">“{{ $testimonial->localized('quote') }}”</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
