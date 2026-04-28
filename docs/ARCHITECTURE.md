# MannaRise Architecture

MannaRise uses a Laravel-first architecture with custom Livewire screens. The goal is to keep the application simple to operate while still leaving clear extension points for devotional plans, reminders, audio, community spaces, and monetization.

## Main Layers

- Eloquent models for business data
- Migrations for database structure
- Seeders for starter devotional, Bible, library, and demo user content
- Livewire components for public pages, authenticated user flows, and admin screens
- Blade layouts and components for reusable UI
- Middleware and policies for access control
- Notifications and console commands for reminder workflows
- Vite and Tailwind for frontend assets

## Primary Modules

- Home and public discovery
- Daily rhythm: verse of the day, affirmation, and Bible-in-a-year assignment
- Spiritual growth score, personalized daily path, and Bible challenge catch-up mode
- Devotionals and devotional categories
- KJV Bible reader and Bible search
- Public-domain spiritual library
- Favorites, journal entries, devotional completions, and streaks
- Prayer requests and public prayer wall
- Testimonies and moderation
- Audio devotionals
- Platform settings
- Reminder settings and devotional reminder delivery
- Admin content management
- Engagement reporting
- Roles and permissions for expanded admin control
- PWA/offline shell support

## Daily Rhythm Design

The daily rhythm is intentionally computed from local data:

- Verse of the day comes from the seeded KJV Bible records.
- Daily affirmation rotates through a curated scripture-based list.
- Bible-in-a-year reading assignment maps the current day of year across the total Bible chapter count.

This keeps the base experience deterministic and offline-friendly. User-specific check-ins and chapter completions are stored separately for growth scoring and catch-up mode.

## Dashboard Strategy

The app does not use Filament. User and admin dashboards are custom Livewire components that share Blade UI patterns. This keeps the product experience consistent across public, user, and admin areas.

## Route Groups

- Public routes: home, daily rhythm, Bible, library, devotionals, audio devotionals, prayer wall, testimony pages
- Guest routes: login and registration
- Auth routes: dashboard, journal, favorites, reminder settings
- Admin routes: dashboard, categories, devotionals, settings, audio devotionals, prayer requests, testimonies, engagement, roles

## Access Control

The first access layer uses `is_admin` and `is_super_admin` flags. The expanded layer uses role and permission tables for more granular admin access. Middleware should remain small and route-focused; business permission checks should stay in models, policies, or dedicated authorization helpers.

## Background Work

Reminder delivery should use scheduled commands and notifications. Long-running or external delivery work should be queueable. Future notification delivery logs should be stored for observability and support.

## Pending Architecture Decisions

- Whether Bible challenge progress should be stored as daily check-ins, chapter completions, or both
- How devotional plans should model flexible length, enrollment, skipped days, and catch-up behavior
- Whether share cards should be generated server-side, client-side, or by a queued image job
- Storage strategy for audio files, transcripts, generated images, and future media assets
- Scope model for church/ministry spaces and group reading plans
- Payment provider, donation model, and entitlements for paid content
- Audit log shape for admin actions and moderation events
