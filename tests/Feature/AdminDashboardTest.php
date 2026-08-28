<?php

namespace Tests\Feature;

use App\Filament\Widgets\LatestOrders;
use App\Filament\Widgets\LowStockProducts;
use App\Filament\Widgets\StatsOverview;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_loads_without_the_filament_branding_widget(): void
    {
        $admin = User::factory()->create(['email' => 'admin@ochotierras.cl']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertDontSee('Documentación');
    }

    public function test_stats_overview_widget_renders_real_numbers(): void
    {
        $this->actingAs(User::factory()->create());

        $product = Product::factory()->create(['stock' => 10, 'price' => 20000]);
        Order::create([
            'customer_name' => 'Cliente Test',
            'customer_email' => 'cliente@example.com',
            'status' => 'PAID',
            'total_amount' => 20000,
            'site_transaction_id' => 'ORD-TEST-STATS',
        ]);

        Livewire::test(StatsOverview::class)
            ->assertSee('Ingresos del Mes')
            ->assertSee('$20.000')
            ->assertSee('Pedidos Pendientes de Pago');
    }

    public function test_latest_orders_widget_renders(): void
    {
        $this->actingAs(User::factory()->create());

        Order::create([
            'customer_name' => 'Cliente Test',
            'customer_email' => 'cliente@example.com',
            'status' => 'PENDING',
            'total_amount' => 15000,
            'site_transaction_id' => 'ORD-TEST-LATEST',
        ]);

        Livewire::test(LatestOrders::class)
            ->assertSee('Cliente Test')
            ->assertSee('Pendiente');
    }

    public function test_low_stock_widget_renders(): void
    {
        $this->actingAs(User::factory()->create());

        $lowStock = Product::factory()->create(['stock' => 2, 'is_active' => true, 'name' => 'Vino Escaso']);
        Product::factory()->create(['stock' => 100, 'is_active' => true, 'name' => 'Vino Sobrado']);

        Livewire::test(LowStockProducts::class)
            ->assertSee('Vino Escaso')
            ->assertDontSee('Vino Sobrado');
    }
}
