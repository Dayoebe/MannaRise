<?php

namespace Tests\Feature;

use App\Models\BibleBook;
use App\Models\BibleVerse;
use App\Support\LanguagePreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguagePreferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_language_switch_persists_choice_and_redirects_to_requested_local_page(): void
    {
        $target = route('daily.localized.show', ['locale' => 'fr', 'date' => '2026-07-01']);

        $this->get(route('language.switch', ['locale' => 'fr', 'redirect' => $target]))
            ->assertRedirect($target)
            ->assertSessionHas(LanguagePreference::SESSION_KEY, 'fr')
            ->assertCookie(LanguagePreference::COOKIE_KEY, 'fr');
    }

    public function test_language_switch_rejects_external_redirects(): void
    {
        $this->get(route('language.switch', ['locale' => 'es', 'redirect' => 'https://example.com/offsite']))
            ->assertRedirect(route('localized.home', ['locale' => 'es']));
    }

    public function test_navbar_uses_selected_language_for_global_links(): void
    {
        $john = BibleBook::create([
            'book_order' => 43,
            'name' => 'John',
            'slug' => 'john',
            'abbreviation' => 'John',
            'testament' => 'New Testament',
            'chapters' => 21,
        ]);

        BibleVerse::create([
            'bible_book_id' => $john->id,
            'language' => 'pt',
            'version' => 'ALMEIDA',
            'chapter' => 3,
            'verse' => 16,
            'text' => 'Porque Deus amou o mundo.',
        ]);

        $this->withSession([LanguagePreference::SESSION_KEY => 'pt'])
            ->get('/about')
            ->assertOk()
            ->assertSee('Português')
            ->assertSee('Explorar')
            ->assertSee('Mais')
            ->assertSee('desktop-nav-menu', false)
            ->assertSee('footer-link-grid', false)
            ->assertSee('footer-language-link-active', false)
            ->assertSee('Discovery')
            ->assertSee(route('localized.home', ['locale' => 'pt']), false)
            ->assertSee(route('daily.localized.show', ['locale' => 'pt', 'date' => today()->toDateString()]), false)
            ->assertSee('language=pt&amp;version=ALMEIDA', false);
    }
}
