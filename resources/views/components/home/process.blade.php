@php
    $steps = __('site.process.steps');
    $icons = ['chat-bubble-left-right', 'map', 'code-bracket-square', 'rocket-launch'];
@endphp

<section id="proceso" class="py-24 sm:py-32">
    <div class="container-site grid gap-16 lg:grid-cols-[0.9fr_1.1fr]">
        <div class="lg:sticky lg:top-32 lg:self-start">
            <x-home.section-heading :eyebrow="__('site.process.eyebrow')" :title-a="__('site.process.title_a')" :title-b="__('site.process.title_b')" :lead="__('site.process.lead')" />
        </div>

        <ol class="relative flex flex-col gap-14 pl-12 sm:pl-16" data-process-track>
            <span class="absolute top-2 bottom-2 left-[15px] w-px bg-line sm:left-[19px]" aria-hidden="true"></span>
            <span data-process-line class="absolute top-2 bottom-2 left-[15px] w-px origin-top scale-y-0 bg-gradient-to-b from-malbec to-sun sm:left-[19px]" aria-hidden="true"></span>

            @foreach ($steps as $index => $step)
                <li class="relative flex flex-col gap-3" data-reveal data-delay="{{ $index * 80 }}">
                    <span class="absolute top-1 -left-12 grid size-8 place-items-center rounded-full border border-malbec/60 bg-ink shadow-[0_0_24px_-4px_color-mix(in_oklab,var(--color-malbec)_70%,transparent)] sm:-left-16 sm:size-10">
                        <span class="size-2.5 rounded-full bg-malbec"></span>
                    </span>
                    <div class="flex items-center gap-4">
                        <span class="font-display text-5xl font-black text-malbec sm:text-6xl">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <x-dynamic-component :component="'heroicon-o-'.$icons[$index]" class="size-5 text-dim" />
                    </div>
                    <h3 class="font-display text-xl font-bold uppercase">{{ $step['title'] }}</h3>
                    <p class="max-w-lg leading-relaxed text-muted">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
