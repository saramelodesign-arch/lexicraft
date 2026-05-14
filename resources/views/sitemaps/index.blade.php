{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($sitemaps as $loc)
    <sitemap>
        <loc>{{ htmlspecialchars($loc, ENT_XML1 | ENT_COMPAT, 'UTF-8') }}</loc>
    </sitemap>
@endforeach
</sitemapindex>
