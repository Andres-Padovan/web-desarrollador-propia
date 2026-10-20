@props(['eyebrow', 'titleA', 'titleB', 'lead' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-5']) }} data-reveal>
    <span class="eyebrow">// {{ $eyebrow }}</span>
    <h2 class="section-title">{{ $titleA }} <span class="text-malbec">{{ $titleB }}</span></h2>
    @if ($lead)
        <p class="max-w-2xl text-lg text-muted">{{ $lead }}</p>
    @endif
</div>
