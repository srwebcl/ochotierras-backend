<?php

namespace Tests\Feature;

use App\Filament\Pages\SiteSettingsPage;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class FrontendRevalidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.frontend.url' => 'https://frontend.test',
            'services.frontend.revalidate_secret' => 'test-secret',
        ]);
    }

    public function test_saving_site_settings_notifies_the_frontend_to_revalidate(): void
    {
        Http::fake(['frontend.test/api/revalidate' => Http::response(['revalidated' => true], 200)]);

        $admin = User::factory()->create(['is_super_admin' => true, 'email' => 'jefe@ochotierras.cl']);

        Livewire::actingAs($admin)
            ->test(SiteSettingsPage::class)
            ->fillForm(['email' => 'nuevo@ochotierras.cl'])
            ->call('save');

        $this->assertEquals('nuevo@ochotierras.cl', SiteSetting::current()->email);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://frontend.test/api/revalidate'
                && $request['secret'] === 'test-secret'
                && $request['tag'] === 'site-settings';
        });
    }

    public function test_saving_a_product_notifies_the_frontend_to_revalidate(): void
    {
        Http::fake(['frontend.test/api/revalidate' => Http::response(['revalidated' => true], 200)]);

        Product::factory()->create();

        Http::assertSent(function ($request) {
            return $request->url() === 'https://frontend.test/api/revalidate'
                && $request['tag'] === 'products';
        });
    }

    public function test_a_failed_revalidation_call_does_not_break_the_save(): void
    {
        Http::fake(['frontend.test/api/revalidate' => Http::response('boom', 500)]);

        $admin = User::factory()->create(['is_super_admin' => true, 'email' => 'jefe@ochotierras.cl']);

        Livewire::actingAs($admin)
            ->test(SiteSettingsPage::class)
            ->fillForm(['email' => 'nuevo2@ochotierras.cl'])
            ->call('save');

        $this->assertEquals('nuevo2@ochotierras.cl', SiteSetting::current()->email);
    }
}
