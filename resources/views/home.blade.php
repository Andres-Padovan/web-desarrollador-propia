@php
    $profile = config('profile');
    $whatsappUrl = 'https://wa.me/'.$profile['whatsapp'].'?text='.rawurlencode(__('site.whatsapp_message'));
@endphp

<x-layouts.site>
    <x-slot:before>
        <x-home.intro :profile="$profile" />
    </x-slot:before>

    <x-home.hero :profile="$profile" :whatsapp-url="$whatsappUrl" />
    <x-home.showreel :profile="$profile" />
    <x-home.metrics :profile="$profile" :projects-count="$projectsCount" />
    <x-home.projects :projects="$projects" :profile="$profile" :total="$projectsCount" featured />
    <x-home.explore />
    @if ($testimonials->isNotEmpty())
        <x-home.testimonials :testimonials="$testimonials" />
    @endif
</x-layouts.site>
