@php
    $profile = config('profile');
    $whatsappUrl = 'https://wa.me/'.$profile['whatsapp'].'?text='.rawurlencode(__('site.whatsapp_message'));
@endphp

<x-layouts.app>
    <x-home.intro :profile="$profile" />
    <x-home.header :whatsapp-url="$whatsappUrl" />

    <main>
        <x-home.hero :profile="$profile" :whatsapp-url="$whatsappUrl" />
        <x-home.showreel :profile="$profile" />
        <x-home.metrics :profile="$profile" :projects-count="$projects->count()" />
        <x-home.services :services="$services" />
        <x-home.projects :projects="$projects" :categories="$categories" :profile="$profile" />
        <x-home.process />
        @if ($testimonials->isNotEmpty())
            <x-home.testimonials :testimonials="$testimonials" />
        @endif
        <x-home.about :profile="$profile" />
        <x-home.stack :profile="$profile" />
        <x-home.contact :profile="$profile" :whatsapp-url="$whatsappUrl" />
    </main>

    <x-home.footer :profile="$profile" />

    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
       class="group fixed right-5 bottom-5 z-50 flex items-center gap-2 rounded-full bg-[#25d366] p-3.5 text-ink shadow-[0_10px_30px_-8px_rgba(37,211,102,0.7)] transition hover:scale-105 sm:right-7 sm:bottom-7"
       aria-label="WhatsApp">
        <span class="absolute inset-0 -z-10 animate-ping-slow rounded-full bg-[#25d366]/40"></span>
        <x-brand-icon name="whatsapp" class="size-6" />
    </a>
</x-layouts.app>
