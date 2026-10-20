@props(['title' => null, 'description' => null, 'contact' => true])

@php
    $profile = config('profile');
    $whatsappUrl = 'https://wa.me/'.$profile['whatsapp'].'?text='.rawurlencode(__('site.whatsapp_message'));
@endphp

{{-- Estructura común de todas las páginas públicas: menú, contenido, contacto y pie. --}}
<x-layouts.app :title="$title" :description="$description">
    {{ $before ?? '' }}
    <x-home.header :whatsapp-url="$whatsappUrl" />

    <main>
        {{ $slot }}

        @if ($contact)
            <x-home.contact :profile="$profile" :whatsapp-url="$whatsappUrl" />
        @endif
    </main>

    <x-home.footer :profile="$profile" />

    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
       class="group fixed right-5 bottom-5 z-50 flex items-center gap-2 rounded-full bg-[#25d366] p-3.5 text-ink shadow-[0_10px_30px_-8px_rgba(37,211,102,0.7)] transition hover:scale-105 sm:right-7 sm:bottom-7"
       aria-label="WhatsApp">
        <span class="absolute inset-0 -z-10 animate-ping-slow rounded-full bg-[#25d366]/40"></span>
        <x-brand-icon name="whatsapp" class="size-6" />
    </a>
</x-layouts.app>
