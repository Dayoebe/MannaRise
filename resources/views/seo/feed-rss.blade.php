<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('seo.site_name') }}</title>
        <link>{{ route('home') }}</link>
        <description>{{ config('seo.description') }}</description>
        <language>{{ str_replace('_', '-', app()->getLocale()) }}</language>
        <lastBuildDate>{{ \Illuminate\Support\Carbon::parse($updatedAt)->toRssString() }}</lastBuildDate>
        <atom:link href="{{ route('seo.feed') }}" rel="self" type="application/rss+xml" />
@foreach ($items as $item)
        <item>
            <title>{{ $item['title'] }}</title>
            <link>{{ $item['url'] }}</link>
            <guid isPermaLink="true">{{ $item['url'] }}</guid>
            <description>{{ $item['summary'] }}</description>
            <category>{{ $item['category'] }}</category>
            <pubDate>{{ \Illuminate\Support\Carbon::parse($item['published_at'])->toRssString() }}</pubDate>
        </item>
@endforeach
    </channel>
</rss>
