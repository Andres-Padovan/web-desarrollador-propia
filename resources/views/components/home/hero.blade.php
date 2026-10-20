@props(['profile', 'whatsappUrl'])

@php
    $isEnglish = app()->getLocale() === 'en';
    $terminal = [
        'sequence' => __('site.hero.sequence'),
        'hint' => __('site.hero.terminal_hint'),
        'timezone' => $profile['timezone'],
        'messages' => [
            'help' => __('site.terminal.help'),
            'about' => __('site.terminal.about'),
            'services' => __('site.terminal.services'),
            'contact' => __('site.terminal.contact'),
            'opening' => __('site.terminal.opening'),
            'notFound' => __('site.terminal.not_found'),
            'hire' => __('site.terminal.hire'),
        ],
        'links' => [
            'whatsapp' => $whatsappUrl,
            'github' => $profile['github'],
            'linkedin' => $profile['linkedin'],
            'en' => route('home.en'),
            'es' => route('home'),
            'services' => \App\Support\LocalizedRoute::to('services'),
            'projects' => \App\Support\LocalizedRoute::to('projects'),
            'process' => \App\Support\LocalizedRoute::to('process'),
        ],
    ];
    $prompt = $profile['handle'].'@mendoza';
@endphp

<section id="inicio" class="relative overflow-hidden pt-28 pb-16 sm:pt-36 sm:pb-24">
    <div class="grid-bg pointer-events-none absolute inset-0"></div>
    <div class="pointer-events-none absolute -top-40 left-1/2 h-[520px] w-[820px] -translate-x-1/2 rounded-full bg-malbec/20 blur-[140px]"></div>
    <div class="pointer-events-none absolute top-1/3 -right-40 h-[380px] w-[380px] animate-float rounded-full bg-sun/10 blur-[120px]"></div>

    <div class="container-site relative">
        <div class="mb-10 flex flex-wrap items-center gap-3" data-reveal>
            <span class="relative flex size-2.5">
                <span class="absolute inline-flex size-full animate-ping-slow rounded-full {{ $profile['available'] ? 'bg-leaf' : 'bg-sun' }}"></span>
                <span class="relative inline-flex size-2.5 rounded-full {{ $profile['available'] ? 'bg-leaf' : 'bg-sun' }}"></span>
            </span>
            <span class="font-mono text-xs tracking-[0.2em] text-muted uppercase"
                  x-data="scramble(@js(__('site.hero.kicker').' · '.$profile['location']))" x-text="output">{{ __('site.hero.kicker') }} · {{ $profile['location'] }}</span>
        </div>

        <h1 class="font-display text-[2.6rem] leading-[0.95] font-black tracking-tight uppercase sm:text-7xl lg:text-[6.4rem]" data-reveal data-delay="100">
            <span class="block">{{ __('site.hero.title_a') }}</span>
            <span class="block bg-gradient-to-r from-malbec via-malbec-soft to-sun bg-clip-text text-transparent">{{ __('site.hero.title_b') }}</span>
        </h1>

        <div class="mt-10 grid gap-10 lg:grid-cols-[1fr_1.15fr] lg:items-end">
            <div class="flex flex-col gap-8" data-reveal data-delay="200">
                <p class="max-w-xl text-lg leading-relaxed text-muted">{{ __('site.hero.lead') }}</p>
                <div class="flex flex-wrap gap-3">
                    <a href="#contacto" class="btn btn-primary">{{ __('site.hero.cta_primary') }} <x-heroicon-m-arrow-right class="size-4" /></a>
                    <a href="{{ \App\Support\LocalizedRoute::to('projects') }}" class="btn btn-ghost">{{ __('site.hero.cta_secondary') }}</a>
                </div>
                <div class="flex items-center gap-5 text-muted">
                    <a href="{{ $profile['github'] }}" target="_blank" rel="noopener" class="transition hover:text-malbec" aria-label="GitHub"><x-brand-icon name="github" /></a>
                    <a href="{{ $profile['linkedin'] }}" target="_blank" rel="noopener" class="transition hover:text-malbec" aria-label="LinkedIn"><x-brand-icon name="linkedin" /></a>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="transition hover:text-malbec" aria-label="WhatsApp"><x-brand-icon name="whatsapp" /></a>
                    <span class="h-px flex-1 bg-line"></span>
                </div>
            </div>

            {{-- Terminal interactiva --}}
            <div class="overflow-hidden rounded-xl border border-line bg-night/90 shadow-[0_40px_120px_-40px_rgba(255,61,127,0.35)] backdrop-blur" data-reveal data-delay="300"
                 x-data="terminal(@js($terminal))" @click="focusInput()">
                <div class="flex items-center gap-2 border-b border-line bg-panel px-4 py-3">
                    <span class="size-3 rounded-full bg-[#ff5f57]"></span>
                    <span class="size-3 rounded-full bg-[#febc2e]"></span>
                    <span class="size-3 rounded-full bg-[#28c840]"></span>
                    <span class="mx-auto font-mono text-xs text-dim">{{ $prompt }}: {{ __('site.hero.path') }}</span>
                </div>

                <div class="grid sm:grid-cols-[1fr_200px]">
                    <div x-ref="screen" class="h-[300px] overflow-y-auto p-5 font-mono text-[13px] leading-relaxed" aria-live="polite">
                        <template x-for="(line, index) in lines" :key="index">
                            <div>
                                <template x-if="line.type === 'cmd'">
                                    <p><span class="text-dim">$</span> <span class="text-malbec" x-text="line.text"></span></p>
                                </template>
                                <template x-if="line.type === 'out'">
                                    <p class="border-l-2 border-malbec/50 pl-3 text-fg/90" x-text="line.text"></p>
                                </template>
                                <template x-if="line.type === 'hint'">
                                    <p class="mt-3 text-dim" x-text="'# ' + line.text"></p>
                                </template>
                            </div>
                        </template>
                        <p x-show="!ready"><span class="text-dim">$</span> <span class="text-malbec" x-text="typing"></span><span class="animate-blink text-malbec">▋</span></p>
                        <form x-show="ready" x-cloak @submit.prevent="run()" class="flex items-center gap-2">
                            <label for="terminal-input" class="text-dim">$</label>
                            <input id="terminal-input" x-ref="input" x-model="input" @keydown.arrow-up.prevent="recall(1)" @keydown.arrow-down.prevent="recall(-1)"
                                   type="text" autocomplete="off" spellcheck="false" aria-label="{{ __('site.hero.terminal_hint') }}"
                                   class="w-full border-0 bg-transparent p-0 font-mono text-[13px] text-malbec caret-malbec outline-none focus:ring-0">
                        </form>
                    </div>

                    <dl class="flex flex-col gap-2.5 border-t border-line p-5 font-mono text-[12px] sm:border-t-0 sm:border-l">
                        <dt class="mb-1 text-dim">// {{ __('site.hero.status') }}</dt>
                        <div class="flex justify-between gap-3"><dt class="text-dim">{{ __('site.hero.role') }}</dt><dd class="text-right">{{ __('site.hero.role_value') }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-dim">{{ __('site.hero.region') }}</dt><dd>Mendoza, AR</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-dim">{{ __('site.hero.experience') }}</dt><dd>{{ $profile['years_experience'] }}+ {{ __('site.hero.years') }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-dim">{{ __('site.hero.stack') }}</dt><dd>laravel · php</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-dim">{{ __('site.hero.local_time') }}</dt><dd x-data="clock(@js($profile['timezone']))" x-text="time" class="tabular-nums">--:--:--</dd></div>
                        <div class="mt-auto flex justify-between gap-3 border-t border-line pt-3">
                            <dt class="text-dim">{{ __('site.hero.available') }}</dt>
                            <dd class="{{ $profile['available'] ? 'text-leaf' : 'text-sun' }}">● {{ $profile['available'] ? __('site.hero.available_yes') : __('site.hero.available_no') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</section>
