<?php

namespace Tests\Unit;

use App\Support\Locales;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LocalesConfigTest extends TestCase
{
    #[Test]
    public function supported_config_matches_industrial_language_set(): void
    {
        $supported = config('locales.supported');

        $this->assertCount(6, $supported);

        foreach (['en', 'pt', 'fr', 'de', 'it', 'es'] as $code) {
            $this->assertArrayHasKey($code, $supported);
            $this->assertArrayHasKey('name', $supported[$code]);
            $this->assertArrayHasKey('native', $supported[$code]);
        }
    }

    #[Test]
    public function locales_helper_reflects_config(): void
    {
        $this->assertSame(array_keys(config('locales.supported')), Locales::codes());
        $this->assertTrue(Locales::isSupported('de'));
        $this->assertFalse(Locales::isSupported('xx'));
    }
}
