# Daily Scripture API Integration

MannaRise stores externally fetched scripture in `daily_scriptures` and reads from that local table on the daily page and dashboard. Page loads do not call external Bible APIs directly. The scheduled command refreshes the local row, and the service can fall back across configured providers if one provider fails.

## Providers

- `bible_api_com` is the default provider and does not require an API key.
- `our_manna` is optional and can be used for verse-of-the-day data.
- `api_bible` is optional and is only used when `API_BIBLE_KEY` and `API_BIBLE_ID` are configured.

## Environment

```env
BIBLE_PROVIDER=bible_api_com
BIBLE_DEFAULT_TRANSLATION=web
BIBLE_FALLBACK_TRANSLATION=kjv
BIBLE_CACHE_TTL=86400
OUR_MANNA_ENABLED=true
API_BIBLE_KEY=
API_BIBLE_ID=
```

## Sync Command

Run the sync manually:

```bash
php artisan mannarise:sync-daily-scripture
```

Useful options:

```bash
php artisan mannarise:sync-daily-scripture --provider=our_manna
php artisan mannarise:sync-daily-scripture --date=2026-05-06
php artisan mannarise:sync-daily-scripture --force
```

The scheduler runs it daily at `03:30` before the existing resource devotion preparation. In production, make sure Laravel's scheduler is active:

```bash
* * * * * cd /path/to/mannarise && php artisan schedule:run >> /dev/null 2>&1
```

## Admin Controls

Admins can manage the integration at `/admin/daily-scriptures`. The screen shows today's stored scripture, provider settings, fallback toggles, API.Bible credential status, recent sync rows, and a manual refresh action.

## Copyright Safety

MannaRise should not scrape or republish copyrighted devotional guide content. External APIs should only be used for scripture/verse data where licensing allows it. Devotional reflections should be original/admin-created content.
