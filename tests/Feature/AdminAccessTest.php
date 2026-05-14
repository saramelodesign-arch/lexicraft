<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_is_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    #[Test]
    public function non_admin_receives_forbidden(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    #[Test]
    public function admin_can_open_dashboard(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)->get('/admin')->assertOk()->assertSee('Editorial overview', false);
    }

    #[Test]
    public function allow_list_email_grants_access_without_flag(): void
    {
        config(['admin.allow_emails' => ['editor@example.com']]);

        $user = User::factory()->create([
            'email' => 'editor@example.com',
            'is_admin' => false,
        ]);

        $this->actingAs($user)->get('/admin')->assertOk();
    }
}
