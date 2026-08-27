<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * La raíz del sitio no tiene contenido público propio (es API + panel
     * de administración), así que redirige directo al login de Filament.
     */
    public function test_the_root_redirects_to_the_admin_panel(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/admin');
    }
}
