<?php

namespace Tests\Feature;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ManageUsers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_regular_user_can_see_the_panel_users_list_but_not_edit_or_delete(): void
    {
        $regular = User::factory()->create(['is_super_admin' => false, 'email' => 'staff@ochotierras.cl']);
        $other = User::factory()->create();

        // Puede entrar y ver la lista.
        $this->actingAs($regular)->get('/admin/users')->assertOk();

        Livewire::test(ManageUsers::class)
            ->assertSuccessful()
            ->assertTableActionVisible('view', record: $other)
            ->assertTableActionHidden('edit', record: $other)
            ->assertTableActionHidden('delete', record: $other)
            ->assertActionHidden('create');

        $this->assertTrue(UserResource::canViewAny());
        $this->assertFalse(UserResource::canEdit($other));
        $this->assertFalse(UserResource::canDelete($other));
        $this->assertFalse(UserResource::canCreate());
    }

    public function test_a_super_admin_can_manage_other_accounts(): void
    {
        $superAdmin = User::factory()->create(['is_super_admin' => true, 'email' => 'jefe@ochotierras.cl']);
        $other = User::factory()->create();

        $this->actingAs($superAdmin)->get('/admin/users')->assertOk();

        Livewire::test(ManageUsers::class)
            ->assertTableActionVisible('edit', record: $other)
            ->assertTableActionVisible('delete', record: $other)
            ->assertActionVisible('create');

        $this->assertTrue(UserResource::canEdit($other));
        $this->assertTrue(UserResource::canDelete($other));
        $this->assertTrue(UserResource::canCreate());
    }

    public function test_any_user_can_reach_their_own_profile_page_to_change_their_password(): void
    {
        $regular = User::factory()->create(['is_super_admin' => false, 'email' => 'staff@ochotierras.cl']);

        $this->actingAs($regular)->get('/admin/profile')->assertOk();
    }
}
