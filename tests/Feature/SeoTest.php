<?php

namespace Tests\Feature;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_core_seo_tags(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<meta name="description"', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('<meta property="og:title"', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
            ->assertSee('application/ld+json', false);
    }

    public function test_robots_txt_is_available(): void
    {
        $this->get(route('seo.robots'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('User-agent: *')
            ->assertSee('Sitemap: '.route('seo.sitemap'));
    }

    public function test_sitemap_xml_includes_static_and_published_devotional_urls(): void
    {
        $category = DevotionalCategory::create([
            'name' => 'Faith',
            'slug' => 'faith',
            'is_active' => true,
        ]);

        $devotional = Devotional::create([
            'devotional_category_id' => $category->id,
            'title' => 'Faith for Today',
            'slug' => 'faith-for-today',
            'content' => 'A devotional reflection for today and every believer growing in faith.',
            'published_at' => now(),
            'is_published' => true,
            'reading_time' => 3,
        ]);

        $this->get(route('seo.sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<urlset', false)
            ->assertSee(route('home'), false)
            ->assertSee(route('devotionals.show', $devotional->slug), false);
    }

    public function test_devotional_page_renders_article_schema(): void
    {
        $category = DevotionalCategory::create([
            'name' => 'Faith',
            'slug' => 'faith',
            'is_active' => true,
        ]);

        $devotional = Devotional::create([
            'devotional_category_id' => $category->id,
            'title' => 'Faith for Today',
            'slug' => 'faith-for-today',
            'bible_reference' => 'Hebrews 11:1',
            'content' => 'Faith is choosing to trust God while taking the next obedient step.',
            'published_at' => now(),
            'is_published' => true,
            'reading_time' => 3,
        ]);

        $this->get(route('devotionals.show', $devotional->slug))
            ->assertOk()
            ->assertSee('Faith for Today | MannaRise')
            ->assertSee('Hebrews 11:1')
            ->assertSee('"@type": "Article"', false);
    }
}
