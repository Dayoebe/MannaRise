# MannaRise Development Plan

MannaRise is a pure Laravel and Livewire devotional platform. The product direction is to keep the public site, user dashboard, and admin dashboard custom-built without Filament.

## Product Baseline

The current baseline covers:

- Public devotional discovery and reading
- KJV Bible reader with search
- Verse of the day, daily affirmation, and Bible-in-a-year challenge
- Public-domain spiritual library
- User registration, login, dashboard, favorites, journal, and reading streaks
- Prayer request submission and public prayer wall
- Testimony submission and moderation
- Custom admin control center for quick content input, settings, moderation, core content, and engagement
- Starter seed data for local development

## Pending Feature Backlog

These are the main pending features to carry forward:

- Persist Bible-in-a-year progress per user instead of computing only the daily assignment
- Add daily rhythm completion tracking for verse, affirmation, and Bible challenge check-ins
- Add devotional plans with plan enrollment, day-by-day progress, plan completion, and reminders
- Add richer notification delivery: email, push, reminder digest, missed-day nudges, and weekly summaries
- Add shareable devotional and verse cards with image export and social previews
- Expand audio devotionals with upload/storage handling, transcript text, duration metadata, and admin publishing workflow
- Add author profiles and contributor attribution for devotionals and audio content
- Add church, ministry, or group spaces with shared reading plans and group prayer walls
- Add donation support, sponsored plans, and optional paid devotional content
- Add content calendar tooling for scheduled devotionals, seasonal campaigns, and editorial review
- Add user preferences for timezone, reminder days, Bible version preference, notification channels, and accessibility settings
- Add moderation workflows for spam, abuse reports, testimony review, prayer request visibility, and admin audit logs
- Add deeper analytics for reading streaks, plan completion, prayer engagement, daily active users, and content performance
- Add localization support for UI text and future translated devotional content
- Add data export/delete flows for privacy and account management

## Near-Term Priorities

1. Persist daily rhythm and Bible challenge progress.
2. Build devotional plans on top of the existing devotional and completion models.
3. Harden reminders and notifications with scheduled command coverage and delivery logs.
4. Add share cards for devotionals, verse of the day, and testimonies.
5. Expand admin tooling for content calendar, moderation, and analytics.

## Architecture Guardrails

- Use normal Laravel models, migrations, policies, notifications, commands, and queues.
- Use Livewire components for public, user, and admin workflows.
- Keep admin screens custom-built in the existing UI system.
- Prefer database-backed progress and engagement data where users need continuity across devices.
- Keep generated daily content deterministic when it can be derived from the existing Bible database.
