{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('front.index') }}</loc>
        <changefreq>hourly</changefreq>
        <priority>1.0</priority>
    </url>

    @foreach ($categories as $category)
    <url>
        <loc>{{ route('front.category', $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    @foreach ($authors as $author)
    <url>
        <loc>{{ route('front.author', $author->slug) }}</loc>
        <lastmod>{{ $author->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

    @foreach ($articles as $article)
    <url>
        <loc>{{ route('front.details', $article->slug) }}</loc>
        <lastmod>{{ $article->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
