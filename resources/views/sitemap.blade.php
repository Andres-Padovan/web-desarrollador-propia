{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach (['home' => 'es', 'home.en' => 'en'] as $routeName => $locale)
    <url>
        <loc>{{ route($routeName) }}</loc>
        <lastmod>{{ $lastModified->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $locale === 'es' ? '1.0' : '0.9' }}</priority>
        <xhtml:link rel="alternate" hreflang="es" href="{{ route('home') }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ route('home.en') }}" />
    </url>
@endforeach
</urlset>
