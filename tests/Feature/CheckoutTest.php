<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function fakeGetnetSession(): void
    {
        Http::fake([
            'sandbox.test.getnet.local/*' => Http::response([
                'requestId' => 'REQ-TEST-123',
                'processUrl' => 'https://sandbox.test.getnet.local/session/REQ-TEST-123',
            ], 200),
            'api.brevo.com/*' => Http::response(['ok' => true], 200),
        ]);
    }

    public function test_can_initiate_checkout_and_creates_pending_order(): void
    {
        $this->fakeGetnetSession();

        $product = Product::factory()->create([
            'stock' => 10,
            'price' => 10000,
            'is_pack' => false,
        ]);

        $payload = [
            'items' => [
                ['id' => $product->id, 'quantity' => 2],
            ],
            'total' => 20000,
            'buyer' => [
                'name' => 'John Doe',
                'email' => 'buyer@example.com',
                'phone' => '+56912345678',
                'address' => 'Calle Falsa 123',
                'city' => 'Santiago',
                'region' => 'Metropolitana',
                'rut' => '12345678-9',
                'document_type' => 'boleta',
            ],
        ];

        $response = $this->postJson('/api/payment/init', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure(['processUrl', 'requestId']);

        $this->assertDatabaseHas('orders', [
            'customer_email' => 'buyer@example.com',
            'status' => 'PENDING',
            'total_amount' => 20000,
        ]);

        Http::assertSent(fn ($request) => str_contains($request->url(), '/api/session'));
    }

    public function test_rejects_checkout_when_stock_is_insufficient(): void
    {
        $this->fakeGetnetSession();

        $product = Product::factory()->create([
            'stock' => 1,
            'price' => 10000,
            'is_pack' => false,
        ]);

        $payload = [
            'items' => [
                ['id' => $product->id, 'quantity' => 5],
            ],
            'total' => 50000,
            'buyer' => [
                'name' => 'Jane Doe',
                'email' => 'buyer2@example.com',
                'phone' => '+56912345678',
                'address' => 'Calle Falsa 456',
                'city' => 'Santiago',
                'region' => 'Metropolitana',
                'rut' => '98765432-1',
                'document_type' => 'boleta',
            ],
        ];

        $response = $this->postJson('/api/payment/init', $payload);

        $response->assertStatus(400)
            ->assertJsonPath('error', "Stock insuficiente para: {$product->name}");

        $this->assertDatabaseMissing('orders', [
            'customer_email' => 'buyer2@example.com',
        ]);
    }
}
