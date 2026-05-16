<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function security_headers_are_emitted_on_public_route(): void
    {
        $response = $this->get('/en');

        $response->assertOk();
        $this->assertSecurityHeaders($response);
    }

    #[Test]
    public function security_headers_are_emitted_on_auth_route(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $this->assertSecurityHeaders($response);
    }

    #[Test]
    public function security_headers_are_emitted_on_admin_route(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $this->assertSecurityHeaders($response);
    }

    private function assertSecurityHeaders(\Illuminate\Testing\TestResponse $response): void
    {
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy');
        $response->assertHeader('Content-Security-Policy');

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("frame-ancestors 'none'", $csp);
        $this->assertStringContainsString("frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://player.vimeo.com", $csp);
        $this->assertStringContainsString("script-src 'self' 'unsafe-inline'", $csp);
    }
}

