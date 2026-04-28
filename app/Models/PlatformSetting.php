<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = [
        'setting_key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    public static function defaults(): array
    {
        return [
            'site.name' => [
                'value' => 'MannaRise',
                'type' => 'string',
                'group' => 'Site',
                'label' => 'Site name',
                'description' => 'Display name used across the public experience.',
            ],
            'site.tagline' => [
                'value' => 'Grow daily',
                'type' => 'string',
                'group' => 'Site',
                'label' => 'Site tagline',
                'description' => 'Short supporting line for the platform.',
            ],
            'site.support_email' => [
                'value' => '',
                'type' => 'string',
                'group' => 'Site',
                'label' => 'Support email',
                'description' => 'Contact email for reader support and ministry operations.',
            ],
            'content.default_reading_time' => [
                'value' => '5',
                'type' => 'integer',
                'group' => 'Content',
                'label' => 'Default reading time',
                'description' => 'Default devotional reading time in minutes.',
            ],
            'daily.verse_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'Daily',
                'label' => 'Verse of the day',
                'description' => 'Show the daily Bible verse module.',
            ],
            'daily.affirmations_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'Daily',
                'label' => 'Daily affirmations',
                'description' => 'Show the daily scripture-based affirmation module.',
            ],
            'daily.bible_challenge_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'Daily',
                'label' => 'Bible-in-a-year challenge',
                'description' => 'Show the daily Bible reading challenge module.',
            ],
            'moderation.prayer_public_default' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'Moderation',
                'label' => 'Prayer public default',
                'description' => 'Default prayer request visibility for public submissions.',
            ],
            'moderation.testimony_requires_approval' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'Moderation',
                'label' => 'Testimony approval',
                'description' => 'Require testimony approval before public display.',
            ],
            'notifications.default_timezone' => [
                'value' => 'Africa/Lagos',
                'type' => 'string',
                'group' => 'Notifications',
                'label' => 'Default timezone',
                'description' => 'Default timezone for devotional reminders.',
            ],
        ];
    }

    public static function value(string $key): mixed
    {
        $definition = self::defaults()[$key] ?? null;

        if (! $definition) {
            return null;
        }

        $setting = self::where('setting_key', $key)->first();

        return self::castValue($setting?->value ?? $definition['value'], $setting?->type ?? $definition['type']);
    }

    public static function write(string $key, mixed $value): void
    {
        $definition = self::defaults()[$key] ?? null;

        if (! $definition) {
            return;
        }

        self::updateOrCreate(
            ['setting_key' => $key],
            [
                'value' => self::serializeValue($value, $definition['type']),
                'type' => $definition['type'],
                'group' => $definition['group'],
                'label' => $definition['label'],
                'description' => $definition['description'],
            ],
        );
    }

    public static function allWithDefaults()
    {
        $existing = self::get()->keyBy('setting_key');

        return collect(self::defaults())->map(function (array $definition, string $key) use ($existing) {
            $setting = $existing->get($key);

            return [
                'key' => $key,
                'value' => self::castValue($setting?->value ?? $definition['value'], $setting?->type ?? $definition['type']),
                'type' => $setting?->type ?? $definition['type'],
                'group' => $setting?->group ?? $definition['group'],
                'label' => $setting?->label ?? $definition['label'],
                'description' => $setting?->description ?? $definition['description'],
            ];
        })->values();
    }

    private static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            default => (string) $value,
        };
    }

    private static function serializeValue(mixed $value, string $type): string
    {
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) (int) $value,
            default => (string) $value,
        };
    }
}
