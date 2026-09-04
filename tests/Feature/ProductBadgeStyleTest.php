<?php

namespace Tests\Feature;

use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductBadgeStyleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_customize_badge_color_and_size_once_badge_text_is_set(): void
    {
        $admin = User::factory()->create(['is_super_admin' => true]);
        $product = Product::factory()->create(['badge_text' => null]);

        Livewire::actingAs($admin)
            ->test(EditProduct::class, ['record' => $product->getRouteKey()])
            // El texto se escribe primero: el color/tamaño solo aparece una
            // vez que hay un badge que personalizar (mismo orden que en el
            // navegador real, gracias a ->live() en badge_text).
            ->set('data.badge_text', 'EXCLUSIVO')
            ->set('data.badge_bg_color', '#58181F')
            ->set('data.badge_text_color', '#FFFFFF')
            ->set('data.badge_size', 'large')
            ->call('save')
            ->assertHasNoFormErrors();

        $product->refresh();
        $this->assertEquals('EXCLUSIVO', $product->badge_text);
        $this->assertEquals('#58181F', $product->badge_bg_color);
        $this->assertEquals('#FFFFFF', $product->badge_text_color);
        $this->assertEquals('large', $product->badge_size);
    }

    public function test_products_api_exposes_the_badge_style_fields(): void
    {
        Product::factory()->create([
            'is_pack' => false,
            'is_active' => true,
            'badge_text' => 'OFERTA',
            'badge_bg_color' => '#000000',
            'badge_text_color' => '#FFFFFF',
            'badge_size' => 'small',
        ]);

        $response = $this->getJson('/api/products');

        $response->assertOk();
        $data = $response->json();
        $this->assertEquals('#000000', $data[0]['badgeBgColor']);
        $this->assertEquals('#FFFFFF', $data[0]['badgeTextColor']);
        $this->assertEquals('small', $data[0]['badgeSize']);
    }
}
