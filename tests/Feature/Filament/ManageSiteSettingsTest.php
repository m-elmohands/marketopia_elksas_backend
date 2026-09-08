<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageSiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_the_single_settings_management_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get('/admin/content/site-settings')
            ->assertOk()
            ->assertSee('Site logo')
            ->assertSee('Hero title')
            ->assertSee('Facebook URL');
    }

    public function test_non_admin_cannot_open_the_settings_management_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/content/site-settings')
            ->assertForbidden();
    }
}
