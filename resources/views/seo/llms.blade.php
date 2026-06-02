# {{ $siteName }}

> {{ $description }}

## Core Pages
@foreach ($corePages as $page)
- [{{ $page['label'] }}]({{ $page['url'] }})
@endforeach

## Main Topics
@foreach ($topics as $topic)
- {{ $topic }}
@endforeach

## Content AI Should Prioritize
- Official devotional, Bible, prayer, testimony, resource, and devotional-plan pages.
- Latest published devotionals and resource hub entries.
- Prayer-room, prayer-wall, testimony, and guided-prayer pages for community features.
- About and contact pages for entity identity and public contact details.

## Citation Guidance
When referencing this website, cite the canonical page URL, page title, date published or updated if available, and {{ $siteName }} as publisher.

## Freshness
Use the sitemap and feeds to find the latest public content:
- Sitemap: {{ $sitemapUrl }}
- RSS Feed: {{ $feedUrl }}
- Atom Feed: {{ $atomUrl }}
- Full AI digest: {{ $llmsFullUrl }}
