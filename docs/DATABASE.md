# MannaRise Database Plan

This document tracks the current data model plus the pending tables needed for the next product features.

## Current Core Tables

## Users

Handles authentication and dashboard access.

Key fields:

- name
- email
- password
- is_admin
- is_super_admin

## Roles and Permissions

Supports granular admin permissions where enabled.

Key tables:

- roles
- permissions
- permission_role
- role_user

## Devotional Categories

Stores devotional topics such as faith, prayer, purpose, healing, family, business, and spiritual growth.

Key fields:

- name
- slug
- description
- is_active

## Devotionals

Stores the main devotional content.

Key fields:

- devotional_category_id
- user_id
- title
- slug
- bible_reference
- bible_text
- content
- reflection_question
- prayer_point
- declaration
- published_at
- is_featured
- is_published
- reading_time

## Journal Entries

Stores personal user reflections.

Key fields:

- user_id
- devotional_id
- title
- content
- entry_date

## Prayer Requests

Stores public and private prayer requests.

Key fields:

- user_id
- prayer_room_id
- name
- email
- title
- body
- is_public
- is_answered
- prayed_count

## Prayer Rooms

Stores focused prayer spaces for healing, family, business, exams, marriage, and salvation.

Key fields:

- name
- slug
- description
- scripture_reference
- accent
- sort_order
- is_active

## Prayer Room Memberships

Tracks which users joined each room plus prayer streaks.

Key fields:

- user_id
- prayer_room_id
- joined_at
- last_prayed_on
- current_streak
- longest_streak
- total_prayers

## Prayer Room Prayers

Stores each "I prayed" event for room activity and user streaks.

Key fields:

- user_id
- prayer_room_id
- prayer_request_id
- prayed_on

## Prayer Request Updates

Stores answered-prayer updates attached to prayer requests.

Key fields:

- prayer_request_id
- user_id
- body
- is_answered_update

## Testimonies

Stores user testimonies with approval moderation.

Key fields:

- user_id
- name
- title
- body
- is_anonymous
- is_approved

## Favorite Devotionals

Connects users to saved devotionals.

Key fields:

- user_id
- devotional_id

## Devotional Completions

Tracks completed devotional readings and supports streak logic.

Key fields:

- user_id
- devotional_id
- completed_on

## Bible Books and Verses

Stores KJV Bible data for the Bible reader, search, verse of the day, and Bible-in-a-year challenge.

Key fields:

- bible_books: book_order, name, slug, abbreviation, testament, chapters
- bible_verses: bible_book_id, version, chapter, verse, text

## Spiritual Library

Stores public-domain books and chapters.

Key fields:

- spiritual_books: title, slug, author, tradition, source, published_year, description, is_public_domain, is_featured
- spiritual_book_chapters: spiritual_book_id, chapter_number, title, content

## Devotional Reminders

Stores user reminder preferences for daily path reminders, missed-day nudges, weekly digest delivery, active weekdays, channels, email opt-out, and timezone-aware send times.

Key fields:

- user_id
- title
- remind_at
- timezone
- days
- email_enabled
- push_enabled
- is_active
- last_sent_at

## Notification Delivery Logs

Stores reminder and digest delivery history for admin visibility, sent mail history, manual send troubleshooting, and failed delivery review.

Key fields:

- user_id
- notification_type
- channel
- status
- subject
- message
- action_url
- error_message
- meta
- sent_at

## Audio Devotionals

Stores audio devotional metadata.

Key fields:

- devotional_id
- user_id
- title
- slug
- description
- audio_url
- duration_seconds
- speaker
- is_published
- published_at

## Platform Settings

Stores admin-managed site, content, daily rhythm, moderation, and notification defaults.

Key fields:

- setting_key
- value
- type
- group
- label
- description

## Computed Features Without Dedicated Tables

The base daily rhythm is computed from existing data:

- Verse of the day uses `bible_verses`.
- Bible-in-a-year assignment uses `bible_books.chapters`.
- Daily affirmations are curated in application code.

Public display can use computed data. Personalized progress uses the tracking tables below.

## Daily Rhythm Check-ins

Tracks whether a user completed the daily verse, affirmation, and challenge.

Suggested fields:

- user_id
- checked_on
- verse_reference
- affirmation_reference
- bible_reading_label
- verse_completed_at
- affirmation_completed_at
- challenge_completed_at

## Bible Challenge Progress

Tracks persistent Bible-in-a-year chapter completion and catch-up mode.

Suggested fields:

- user_id
- bible_book_id
- chapter
- assigned_on
- completed_at
- source_plan

## User Spiritual Profiles

Stores the selected season for a user's personalized daily path.

Suggested fields:

- user_id
- season

## Pending Tables

## Devotional Plans

Needed for multi-day guided reading plans.

Suggested tables:

- devotional_plans
- devotional_plan_days
- devotional_plan_enrollments
- devotional_plan_progress

Suggested fields:

- title
- slug
- description
- duration_days
- starts_on
- is_published
- user_id
- devotional_id
- day_number
- completed_at

## Share Cards

Needed for generated devotional, verse, affirmation, and testimony cards.

Suggested fields:

- user_id
- shareable_type
- shareable_id
- title
- image_path
- public_token
- expires_at

## Admin Audit Logs

Needed for moderation, permission, and content governance.

Suggested fields:

- user_id
- action
- auditable_type
- auditable_id
- before
- after
- ip_address

## Ministry Spaces

Needed for churches, ministries, and groups.

Suggested tables:

- ministries
- ministry_user
- ministry_reading_plans
- ministry_prayer_requests

## Donations and Paid Content

Needed if the product adds donations, subscriptions, or paid devotional plans.

Suggested tables:

- donations
- subscriptions
- products
- entitlements

## User Preferences

Needed for personalization.

Suggested fields:

- user_id
- timezone
- bible_version
- reminder_channel
- reading_goal
- font_scale
- locale
