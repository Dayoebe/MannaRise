# MannaRise Database Plan

## Users

The users table will handle authentication and dashboard access. The MVP should include an `is_admin` boolean column for admin access.

## Devotional Categories

Stores devotional topics such as faith, prayer, purpose, healing, family, business, and spiritual growth.

Suggested fields:

- name
- slug
- description
- is_active

## Devotionals

Stores the main devotional content.

Suggested fields:

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

Suggested fields:

- user_id
- devotional_id
- title
- content
- entry_date

## Prayer Requests

Stores public and private prayer requests.

Suggested fields:

- user_id
- name
- email
- title
- body
- is_public
- is_answered
- prayed_count

## Testimonies

Stores user testimonies with approval moderation.

Suggested fields:

- user_id
- name
- title
- body
- is_anonymous
- is_approved

## Favorite Devotionals

Connects users to saved devotionals.

Suggested fields:

- user_id
- devotional_id

## Devotional Completions

Tracks completed readings and supports streak logic.

Suggested fields:

- user_id
- devotional_id
- completed_on
