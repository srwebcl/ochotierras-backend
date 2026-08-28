<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_regular_user_cannot_see_or_open_the_panel_users_screen(): void
    {
        $regular = User::factory()->create(['is_super_admin' => false, 'email' => 'staff@ochotierras.cl']);

        // No debe aparecer en el menú lateral.
        $dashboard = $this->actingAs($regular)->get('/admin');
        $dashboard->assertDontSee('Usuarios del Panel');

        // Y aunque adivine la URL, no debe poder entrar.
        $this->actingAs($regular)->get('/admin/users')->assertForbidden();
    }

    public function test_a_regular_user_cannot_delete_other_accounts_via_the_backend(): void
    {
        $regular = User::factory()->create(['is_super_admin' => false, 'email' => 'staff@ochotierras.cl']);
        $other = User::factory()->create();

        $this->actingAs($regular);

        $this->assertFalse(\App\Filament\Resources\UserResource::canDelete($other));
        $this->assertFalse(\App\Filament\Resources\UserResource::canEdit($other));
    }

    public function test_a_super_admin_can_manage_other_accounts(): void
    {
        $superAdmin = User::factory()->create(['is_super_admin' => true, 'email' => 'jefe@ochotierras.cl']);
        $other = User::factory()->create();

        $this->actingAs($superAdmin)->get('/admin/users')->assertOk();
        $this->assertTrue(\App\Filament\Resources\UserResource::canEdit($other));
        $this->assertTrue(\App\Filament\Resources\UserResource::canDelete($other));
    }

    public function test_any_user_can_reach_their_own_profile_page_to_change_their_password(): void
    {
        $regular = User::factory()->create(['is_super_admin' => false, 'email' => 'staff@ochotierras.cl']);

        $this->actingAs($regular)->get('/admin/profile')->assertOk();
    }
}
