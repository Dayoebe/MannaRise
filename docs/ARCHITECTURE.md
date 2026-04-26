# MannaRise Architecture

MannaRise will use a simple Laravel-first architecture with custom Livewire screens.

## Main Layers

- Models for business data
- Migrations for database structure
- Livewire components for public pages, user dashboard, and admin screens
- Blade layouts for reusable UI
- Policies and middleware for access control
- Seeders for starter devotional content

## Dashboard Strategy

The dashboard will not use Filament. Admin and user dashboards will be built with Livewire components and reusable Blade partials.

## Primary Modules

- Devotionals
- Devotional categories
- Favorites
- Journal entries
- Prayer requests
- Testimonies
- Devotional completions
- Admin content management

## Roles

The MVP will begin with a simple `is_admin` boolean on users. This keeps the first version light. A full role and permission system can be added later if the project grows.

## Route Groups

- Public routes for landing page and devotionals
- Auth routes for user dashboard and journal
- Admin routes for content management

## Long-term Expansion

Future versions can add churches, authors, devotional plans, audio devotionals, notifications, PWA support, and paid content.
