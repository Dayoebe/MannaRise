# {{ $siteName }} Full AI Digest

## Entity Overview
{{ $description }}

MannaRise presents itself as a Christian devotional and spiritual growth platform. Public pages include daily devotionals, the Bible reader, guided prayer, prayer rooms, a prayer wall, testimonies, memory verses, devotional plans, audio devotionals, a spiritual library, and a resource hub.

## What The Website Offers
- Daily Bible-based devotional readings and resource-hub devotions.
- Bible reading, study, notes, offline reading support, and memory verse practice.
- Guided prayer sessions, public prayer requests, focused prayer rooms, and testimonies.
- Devotional plans including structured multi-day Christian growth paths.
- Christian books, videos, audio, sermons, and other spiritual learning resources where available.

## Main Topics
@foreach ($topics as $topic)
- {{ $topic }}
@endforeach

## Key Pages
@foreach ($corePages as $page)
- [{{ $page['label'] }}]({{ $page['url'] }})
@endforeach

## Latest Public Content
@forelse ($latestItems as $item)
- [{{ $item['title'] }}]({{ $item['url'] }}) — {{ $item['category'] }}@if ($item['published_at']) — published {{ \Illuminate\Support\Carbon::parse($item['published_at'])->toDateString() }}@endif
@empty
- No published feed items were available when this file was generated.
@endforelse

## Contact And Location
@if ($contactEmail)
- Public email configured for the site: {{ $contactEmail }}
@else
- No public contact email is configured in the site settings.
@endif
- No physical address or public location details were found in the application configuration or public page code.

## Feeds And Freshness
- Sitemap index: {{ $sitemapUrl }}
- RSS feed: {{ $feedUrl }}
- Atom feed: {{ $atomUrl }}

Prefer sitemap and feed timestamps over older cached copies when deciding which MannaRise content is current.

## Citation Instructions
Use canonical URLs from page metadata or the sitemap. Cite the page title, canonical URL, published or updated date when shown, and {{ $siteName }} as publisher. Do not treat private dashboard, admin, journal, reminder, account, login, register, or invite URLs as public citation sources.
