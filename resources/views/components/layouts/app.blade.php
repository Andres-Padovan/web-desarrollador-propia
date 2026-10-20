@props(['title' => null, 'description' => null])

@php
    $locale = app()->getLocale();
    $profile = config('profile');
    $title = $title ?? __('site.meta.title');
    $description = $description ?? __('site.meta.description');
    $canonical = url()->current();
    $alternates = ['es' => \App\Support\LocalizedRoute::alternate('es'), 'en' => \App\Support\LocalizedRoute::alternate('en')];
    $ogImage = asset('images/og-andres-padovan.jpg');
    $person = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $profile['name'],
        'jobTitle' => __('site.hero.kicker'),
        'url' => route('home'),
        'image' => asset($profile['photo']),
        'email' => 'mailto:'.$profile['email'],
        'telephone' => '+'.$profile['whatsapp'],
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'San Martín', 'addressRegion' => 'Mendoza', 'addressCountry' => 'AR'],
        'alumniOf' => ['@type' => 'EducationalOrganization', 'name' => __('site.about.degree')],
        'knowsAbout' => $profile['stack'],
        'sameAs' => [$profile['linkedin'], $profile['github']],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="theme-color" content="#09090d">
    <link rel="canonical" href="{{ $canonical }}">
    <link rel="alternate" hreflang="es" href="{{ $alternates['es'] }}">
    <link rel="alternate" hreflang="en" href="{{ $alternates['en'] }}">
    <link rel="alternate" hreflang="x-default" href="{{ $alternates['es'] }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $profile['name'] }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="{{ $locale === 'en' ? 'en_US' : 'es_AR' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <script type="application/ld+json">{!! json_encode($person, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="noise min-h-screen overflow-x-hidden">
    <div data-scroll-progress class="fixed inset-x-0 top-0 z-[70] h-0.5 origin-left scale-x-0 bg-gradient-to-r from-malbec via-malbec-soft to-sun"></div>

    {{ $slot }}

    @livewireScriptConfig
</body>
</html>
