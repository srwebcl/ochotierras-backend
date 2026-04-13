<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->truncate();

        $products = [
            [
                'id' => 1,
                'name' => "Chardonnay Reserva",
                'slug' => "chardonnay-reserva",
                'subtitle' => "Reserva",
                'type' => "Blanco",
                'price' => 9000,
                'stock' => 50,
                'image' => "bottles/chardonnay-reserva.webp",
                'accent_color' => "#D4AF37",
                'featured_description' => "Fresco, mineral y elegante. La expresión pura del Limarí.",
                'is_active' => true,
                'is_featured' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => "Cabernet Sauvignon Reserva Especial",
                'slug' => "cabernet-sauvignon-reserva-especial",
                'subtitle' => "Reserva Especial",
                'type' => "Tinto",
                'price' => 12900,
                'stock' => 5,
                'image' => "bottles/reserva-especial-cabernet.webp",
                'accent_color' => "#500000",
                'featured_description' => "Un Cabernet Sauvignon con carácter, estructurado.",
                'is_active' => true,
                'is_featured' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => "Syrah Reserva Especial",
                'slug' => "syrah-reserva-especial",
                'subtitle' => "Syrah",
                'type' => "Tinto",
                'price' => 12900,
                'stock' => 20,
                'image' => "bottles/reserva-especial-syrah.webp",
                'accent_color' => "#500000",
                'featured_description' => "Intenso y especiado, reflejo del Valle del Limarí.",
                'is_active' => true,
                'is_featured' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => "Reserva Privada (Carmenere-Syrah)",
                'slug' => "reserva-privada-carmenere-syrah",
                'subtitle' => "Reserva Privada",
                'type' => "Alta Gama",
                'price' => 18900,
                'stock' => 15,
                'image' => "bottles/reserva-privada-syrah.webp",
                'accent_color' => "#b91c1c",
                'featured_description' => "Elegancia estructural. Un tinto con carácter.",
                'is_active' => true,
                'is_featured' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => "Gran Reserva",
                'slug' => "gran-reserva",
                'subtitle' => "Icono",
                'type' => "Icono",
                'price' => 24900,
                'stock' => 8,
                'image' => "bottles/vino-gran-reserva-24-barricas.webp",
                'accent_color' => "#1a1a1a",
                'featured_description' => "24 meses en barrica. Nuestra obra maestra.",
                'is_active' => true,
                'is_featured' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'name' => "Pack Degustación",
                'slug' => "pack-degustacion",
                'subtitle' => "Pack",
                'type' => "Pack",
                'price' => 35000,
                'stock' => 0,
                'image' => "bottles/pack-degustacion.webp",
                'accent_color' => "#000000",
                'featured_description' => "Selección de nuestros mejores vinos.",
                'is_active' => true,
                'is_featured' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}
