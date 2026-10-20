@props(['profile', 'projectsCount'])

@php
    $metrics = [
        ['value' => $profile['years_experience'], 'decimals' => 0, 'suffix' => '+', 'label' => __('site.metrics.years')],
        ['value' => (float) str_replace(',', '.', $profile['gpa']), 'decimals' => 2, 'suffix' => '', 'label' => __('site.metrics.gpa')],
        $projectsCount > 0
            ? ['value' => $projectsCount, 'decimals' => 0, 'suffix' => '', 'label' => __('site.metrics.projects')]
            : ['value' => count($profile['stack']), 'decimals' => 0, 'suffix' => '', 'label' => __('site.metrics.stack')],
        ['value' => 2, 'decimals' => 0, 'suffix' => '', 'label' => __('site.metrics.languages')],
    ];
    $words = explode(' ', __('site.manifesto.text'));
@endphp

<section class="border-y border-line/60 bg-night py-20 sm:py-28">
    <div class="container-site flex flex-col gap-24">
        <dl class="grid grid-cols-2 gap-x-6 gap-y-12 lg:grid-cols-4">
            @foreach ($metrics as $index => $metric)
                <div class="flex flex-col gap-3" data-reveal data-delay="{{ $index * 100 }}">
                    <dd class="font-display text-5xl font-bold tracking-tight text-fg tabular-nums sm:text-6xl" x-data="counter({{ $metric['value'] }}, {{ $metric['decimals'] }})">
                        <span x-text="formatted">{{ $metric['decimals'] ? $profile['gpa'] : $metric['value'] }}</span><span class="text-malbec">{{ $metric['suffix'] }}</span>
                    </dd>
                    <dt class="font-mono text-xs tracking-[0.18em] text-muted uppercase">{{ $metric['label'] }}</dt>
                </div>
            @endforeach
        </dl>

        <blockquote data-manifesto class="max-w-5xl">
            <span class="mb-6 block font-display text-7xl leading-none text-malbec" aria-hidden="true">“</span>
            <p class="font-display text-2xl leading-[1.25] font-bold uppercase sm:text-4xl lg:text-[2.75rem]">
                @foreach ($words as $word)
                    <span class="word">{{ $word }}</span>
                @endforeach
                <span class="highlight-sweep">{{ __('site.manifesto.highlight') }}</span>
            </p>
        </blockquote>
    </div>
</section>
