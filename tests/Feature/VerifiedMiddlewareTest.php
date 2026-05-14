<?php

namespace Tests\Feature;

use Tests\TestCase;

class VerifiedMiddlewareTest extends TestCase
{
    public function test_root_redirects_to_localized_home(): void
    {
        $this->get('/')
            ->assertRedirect(route('home', ['locale' => 'en']));
    }
}
