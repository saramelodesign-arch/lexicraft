{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($urls as $loc)
    <url>
        <loc>{{ htmlspecialchars($loc, ENT_XML1 | ENT_COMPAT, 'UTF-8') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
@endforeach
</urlset>
