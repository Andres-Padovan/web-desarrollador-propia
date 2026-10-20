{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($pages as [$name, $parameters, $priority])
    @foreach (['es' => $name, 'en' => $name.'.en'] as $locale => $routeName)
    <url>
        <loc>{{ route($routeName, $parameters) }}</loc>
        <lastmod>{{ $lastModified->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $locale === 'es' ? $priority : number_format($priority - 0.1, 1) }}</priority>
        <xhtml:link rel="alternate" hreflang="es" href="{{ route($name, $parameters) }}" />
        <xhtml:link rel="alternate" hreflang="en" href="{{ route($name.'.en', $parameters) }}" />
    </url>
    @endforeach
@endforeach
</urlset>
