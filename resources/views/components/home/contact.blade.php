@props(['profile', 'whatsappUrl'])

<section id="contacto" class="relative overflow-hidden py-24 sm:py-32">
    <div class="grid-bg pointer-events-none absolute inset-0 opacity-60"></div>

    <div class="container-site relative flex flex-col gap-20">
        {{-- CTA --}}
        <div class="relative overflow-hidden rounded-3xl border border-malbec/30 bg-gradient-to-br from-malbec-deep/60 via-panel to-panel p-8 sm:p-14" data-reveal>
            <div class="pointer-events-none absolute -top-24 -right-24 h-72 w-72 animate-float rounded-full bg-malbec/30 blur-[100px]"></div>
            <div class="relative flex flex-col items-start gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="flex flex-col gap-4">
                    <h2 class="font-display text-3xl leading-tight font-black uppercase sm:text-5xl">{{ __('site.cta.title_a') }}<br><span class="text-malbec">{{ __('site.cta.title_b') }}</span></h2>
                    <p class="max-w-xl text-lg text-fg/80">{{ __('site.cta.text') }}</p>
                </div>
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn shrink-0 bg-[#25d366] text-ink hover:shadow-[0_10px_40px_-8px_rgba(37,211,102,0.7)]">
                    <x-brand-icon name="whatsapp" class="size-4" /> {{ __('site.cta.button') }}
                </a>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="grid gap-14 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="flex flex-col gap-10">
                <x-home.section-heading :eyebrow="__('site.contact.eyebrow')" :title-a="__('site.contact.title_a')" :title-b="__('site.contact.title_b')" :lead="__('site.contact.lead')" />

                <div class="flex flex-col gap-3" data-reveal>
                    <p class="font-mono text-[11px] tracking-[0.2em] text-dim uppercase">{{ __('site.contact.or') }}</p>
                    <div class="flex items-center justify-between gap-4 rounded-xl border border-line bg-panel p-4" x-data="copy(@js($profile['email']))">
                        <span class="flex min-w-0 items-center gap-3">
                            <x-heroicon-o-envelope class="size-5 shrink-0 text-malbec" />
                            <a x-ref="value" href="mailto:{{ $profile['email'] }}" class="truncate font-mono text-sm hover:text-malbec">{{ $profile['email'] }}</a>
                        </span>
                        <button type="button" @click="copy()" class="shrink-0 font-mono text-[11px] tracking-widest text-muted uppercase transition hover:text-malbec"
                                x-text="copied ? @js(__('site.contact.copied')) : @js(__('site.contact.copy'))">{{ __('site.contact.copy') }}</button>
                    </div>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl border border-line bg-panel p-4 transition hover:border-[#25d366]/60">
                        <x-brand-icon name="whatsapp" class="size-5 text-[#25d366]" />
                        <span class="font-mono text-sm">{{ $profile['whatsapp_display'] }}</span>
                    </a>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ $profile['linkedin'] }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl border border-line bg-panel p-4 transition hover:border-malbec/60">
                            <x-brand-icon name="linkedin" class="size-5 text-malbec" /><span class="font-mono text-sm">LinkedIn</span>
                        </a>
                        <a href="{{ $profile['github'] }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl border border-line bg-panel p-4 transition hover:border-malbec/60">
                            <x-brand-icon name="github" class="size-5 text-malbec" /><span class="font-mono text-sm">GitHub</span>
                        </a>
                    </div>
                </div>
            </div>

            <div data-reveal data-delay="150">
                <livewire:contact-form />
            </div>
        </div>
    </div>
</section>
