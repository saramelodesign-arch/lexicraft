<?php

namespace Tests\Feature;

use App\Support\Locales;
use Database\Seeders\DomainsSeeder;
use Database\Seeders\LanguagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DomainPagesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function domains_index_renders_with_tree(): void
    {
        $this->seed([LanguagesSeeder::class, DomainsSeeder::class]);

        $this->get(route('domains.index', ['locale' => 'en']))
            ->assertOk()
            ->assertSee('Footwear', false)
            ->assertSee('Industrial domains', false);
    }

    #[Test]
    public function domain_show_uses_localized_slug_and_seo(): void
    {
        $this->seed([LanguagesSeeder::class, DomainsSeeder::class]);

        $this->get(route('domains.show', ['locale' => 'en', 'slug' => 'leather-goods']))
            ->assertOk()
            ->assertSee('Leather goods', false)
            ->assertSee('rel="canonical"', false)
            ->assertSee('hreflang="en"', false)
            ->assertSee('hreflang="pt"', false);

        $this->get(route('domains.show', ['locale' => 'pt', 'slug' => 'marroquinaria']))
            ->assertOk()
            ->assertSee('Marroquinaria', false);
    }

    #[Test]
    public function localized_url_maps_domain_slug_to_target_locale(): void
    {
        $this->seed([LanguagesSeeder::class, DomainsSeeder::class]);

        $this->get(route('domains.show', ['locale' => 'en', 'slug' => 'leather-goods']));

        $ptUrl = Locales::localizedUrl('pt');

        $this->assertStringContainsString('marroquinaria', $ptUrl);
        $this->assertMatchesRegularExpression('#/pt/domains/#', $ptUrl);
    }
}
