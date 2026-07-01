@php echo '<'.'?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL; @endphp
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>{{ config('seo.site_name') }}</title>
    <id>{{ route('home') }}</id>
    <link href="{{ route('home') }}" />
    <link href="{{ route('seo.feed.atom') }}" rel="self" type="application/atom+xml" />
    <updated>{{ \Illuminate\Support\Carbon::parse($updatedAt)->toAtomString() }}</updated>
    <subtitle>{{ config('seo.description') }}</subtitle>
@foreach ($items as $item)
    <entry>
        <title>{{ $item['title'] }}</title>
        <id>{{ $item['url'] }}</id>
        <link href="{{ $item['url'] }}" />
        <updated>{{ \Illuminate\Support\Carbon::parse($item['updated_at'])->toAtomString() }}</updated>
        <published>{{ \Illuminate\Support\Carbon::parse($item['published_at'])->toAtomString() }}</published>
        <summary>{{ $item['summary'] }}</summary>
        <category term="{{ $item['category'] }}" />
    </entry>
@endforeach
</feed>
