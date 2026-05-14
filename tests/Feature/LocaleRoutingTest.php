<?php

namespace Tests\Feature;

use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LocaleRoutingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function root_redirects_to_fallback_when_no_preference(): void
    {
        $this->get('/')
            ->assertRedirect(route('home', ['locale' => 'en']));
    }

    #[Test]
    public function root_redirects_using_session_locale(): void
    {
        $this->withSession(['locale' => 'pt'])
            ->get('/')
            ->assertRedirect(route('home', ['locale' => 'pt']));
    }

    #[Test]
    public function root_accepts_legacy_query_locale_parameter(): void
    {
        $this->get('/?locale=de')
            ->assertRedirect(route('home', ['locale' => 'de']));
    }

    #[Test]
    public function home_resolves_for_each_supported_locale(): void
    {
        foreach (Locales::codes() as $code) {
            $this->get(route('home', ['locale' => $code]))
                ->assertOk();
        }
    }

    #[Test]
    public function invalid_locale_prefix_returns_not_found(): void
    {
        $this->get('/xx')->assertNotFound();
    }

    #[Test]
    public function glossary_letter_route_is_prefixed(): void
    {
        $this->get(route('glossary.letter', ['locale' => 'fr', 'letter' => 'm']))
            ->assertOk()
            ->assertViewHas('letter', 'M')
            ->assertViewHas('locale', 'fr');
    }
}
