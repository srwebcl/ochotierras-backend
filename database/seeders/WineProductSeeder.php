<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WineProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reserva Especial
        $catReservaEspecial = Category::firstOrCreate(
            ['slug' => 'reserva-especial'],
            ['name' => 'Reserva Especial', 'is_active' => true, 'sort_order' => 1]
        );

        // Reserva Especial Cabernet Sauvignon (Cosecha 2019)
        Product::updateOrCreate(
            ['slug' => 'reserva-especial-cabernet-sauvignon-2019'],
            [
                'name' => 'Reserva Especial Cabernet Sauvignon',
                'subtitle' => 'Cosecha 2019',
                'category_id' => $catReservaEspecial->id,
                'type' => 'Tinto',
                'price' => 14900, // Placeholder price
                'stock' => 100,
                'is_active' => true,
                'vintage_year' => 2019,
                'strain' => '100% Cabernet Sauvignon',
                'origin' => 'Valle del Limarí, Viñedos propios',
                'description' => 'De un brillante y profundo color rubí con una intensa concentración. Contiene un aroma con notas dulces de cassis, frambuesa fresca y menta, con una leve insinuación de nota herbal. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad.',
                'image' => 'products/reserva-especial-cabernet.jpg', // Placeholder path
                'technical_details' => [
                    'analysis' => [
                        'alcohol' => '13,5°',
                        'residual_sugar' => '3,4 g/l',
                        'total_ph' => '3,79',
                        'volatile_acidity' => '0,83 g/l',
                        'total_acidity' => '3,71 g/l',
                    ],
                    'tasting_notes' => 'De un brillante y profundo color rubí con una intensa concentración. Contiene un aroma con notas dulces de cassis, frambuesa fresca y menta, con una leve insinuación de nota herbal. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad. En el paladar destacan las frutas negras maduras, cassis y frambuesa, con granito mineral. Pimienta blanca y crema de chocolate, con notas florales persistentes, bien integradas con taninos maduros y elegantes con fina vainilla.',
                    'origin_details' => 'Viñedo Parcela 44',
                    'harvest_type' => 'Manual (2019)',
                    'aging_potential' => '10 a 12 años', // Inferred from similar wines or standard
                    'wood_type' => '10 a 12 meses en Roble Francés',
                    'closure_type' => 'Corcho natural',
                    'awards' => [
                        ['award' => '95 PUNTOS: Guía Palacio de Hierro, México 2013'],
                        ['award' => '90 PUNTOS: Guía Peñín 2012'],
                        ['award' => 'SILVER: Monde Selection Bruxelles 2008'],
                        ['award' => 'COMMENDED: International Wine Challenge, London 2008'],
                        ['award' => '90 PUNTOS: Guía Descorchados 2010'],
                    ]
                ]
            ]
        );

        // Reserva Especial Ensamblaje Cabernet S./Syrah (Cosecha 2022)
        Product::updateOrCreate(
            ['slug' => 'reserva-especial-ensamblaje-2022'],
            [
                'name' => 'Reserva Especial Ensamblaje',
                'subtitle' => 'Cabernet S. / Syrah - Cosecha 2022',
                'category_id' => $catReservaEspecial->id,
                'type' => 'Tinto',
                'price' => 14900,
                'stock' => 100,
                'is_active' => true,
                'vintage_year' => 2022,
                'strain' => 'Cabernet Sauvignon / Syrah',
                'origin' => 'Valle del Limarí, Viñedos propios',
                'description' => 'De color brillante y profundo color rubí con una intensa concentración. Con aroma a notas dulces de cassis, frambuesa fresca y menta. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad.',
                'image' => 'products/reserva-especial-ensamblaje.jpg',
                'technical_details' => [
                    'analysis' => [
                        'alcohol' => '12,5°',
                        'residual_sugar' => '1,98 g/l',
                        'total_ph' => '3,71',
                        'volatile_acidity' => '0,76 g/l',
                        'total_acidity' => '4,28 g/l',
                    ],
                    'tasting_notes' => 'De color brillante y profundo color rubí con una intensa concentración. Con aroma a notas dulces de cassis, frambuesa fresca y menta, con una leve insinuación de nota herbal. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad. En el paladar destacan las frutas negras maduras, cassis y frambuesa, con granito mineral. Pimienta blanca y crema de chocolate, bien integradas con taninos maduros y elegantes.',
                    'origin_details' => 'Viñedo Parcela 44',
                    'harvest_type' => 'Manual (2022)',
                    'wood_type' => '10 a 12 meses en Roble Francés',
                    'closure_type' => 'Corcho natural',
                ]
            ]
        );

        // 2. Reserva Privada
        $catReservaPrivada = Category::firstOrCreate(
            ['slug' => 'reserva-privada'],
            ['name' => 'Reserva Privada', 'is_active' => true, 'sort_order' => 2]
        );

        // Reserva Privada Syrah (Cosecha 2020)
        Product::updateOrCreate(
            ['slug' => 'reserva-privada-syrah-2020'],
            [
                'name' => 'Reserva Privada Syrah',
                'subtitle' => 'Cosecha 2020',
                'category_id' => $catReservaPrivada->id,
                'type' => 'Tinto',
                'price' => 18900,
                'stock' => 80,
                'is_active' => true,
                'vintage_year' => 2020,
                'strain' => '100% Syrah',
                'origin' => 'Valle del Limarí, Viñedos propios',
                'description' => 'Es un vino equilibrado, con notas a frutos rojos, taninos suaves y un color rojo intenso. Se describe como "Una experiencia que no puedes dejar de vivir".',
                'image' => 'products/reserva-privada-syrah.jpg',
                'technical_details' => [
                    'analysis' => [
                        'alcohol' => '15,0°',
                        'residual_sugar' => '2,75 g/l',
                        'total_ph' => '3,71',
                        'volatile_acidity' => '0,62 g/l',
                        'total_acidity' => '3,08 g/l',
                    ],
                    'tasting_notes' => 'Resultado de la cosecha de uvas de un pequeño viñedo caracterizado por suelo franco arenoso y alta mineralidad. Es un vino equilibrado, con notas a frutos rojos, taninos suaves y un color rojo intenso. Se describe como "Una experiencia que no puedes dejar de vivir".',
                    'origin_details' => 'Viñedo Parcela 44 (Suelo franco arenoso, alta mineralidad)',
                    'harvest_type' => 'Manual (2020)',
                    'wood_type' => '10 a 12 meses en Roble Francés',
                    'closure_type' => 'Corcho natural',
                    'awards' => [
                        ['award' => '92 PUNTOS: Guía Peñín 2013'],
                        ['award' => '96 PUNTOS: Guía Palacio de Hierro, 2013 (México)'],
                        ['award' => '90 PUNTOS: Guía Peñín 2012'],
                        ['award' => '88 PUNTOS: Stephen Tanzer, May 2011'],
                        ['award' => 'SILVER MEDAL: Concours Mondial Bruxelles-Chile 2007'],
                        ['award' => 'SILVER: Grand Catador Hyatt 2008'],
                    ]
                ]
            ]
        );

        // Reserva Privada Carmenere (Cosecha 2021)
        Product::updateOrCreate(
            ['slug' => 'reserva-privada-carmenere-2021'],
            [
                'name' => 'Reserva Privada Carmenere',
                'subtitle' => 'Cosecha 2021',
                'category_id' => $catReservaPrivada->id,
                'type' => 'Tinto',
                'price' => 18900,
                'stock' => 80,
                'is_active' => true,
                'vintage_year' => 2021,
                'strain' => '100% Carmenere',
                'origin' => 'Valle del Limarí, Viñedos propios',
                'description' => 'De color violeta intenso y profundo. En nariz resaltan notas a frutas rojas maduras y un final especiado. En boca muestra notas frescas y dulces con sabor a cereza y tabaco.',
                'image' => 'products/reserva-privada-carmenere.jpg',
                'technical_details' => [
                    'analysis' => [
                        'alcohol' => '14,5°',
                        'residual_sugar' => '1,81 g/l',
                        'total_ph' => '3,79',
                        'volatile_acidity' => '0,79 g/l',
                        'total_acidity' => '3,76 g/l',
                    ],
                    'tasting_notes' => 'De color violeta intenso y profundo. En nariz resaltan notas a frutas rojas maduras y un final especiado. En boca muestra notas frescas y dulces con sabor a cereza y tabaco, con un buen equilibrio entre la fruta y el roble francés, y una buena acidez. Presenta taninos suaves y redondos con un buen volumen en boca. Ideal para carnes como cordero y guisos. Servir a 16°C.',
                    'origin_details' => 'Viñedos propios',
                    'harvest_type' => 'Manual (2021)',
                    'wood_type' => '12 meses en Roble Francés',
                    'closure_type' => 'Corcho natural',
                    'presentation' => 'Producción: 10.000 botellas'
                ]
            ]
        );

        // 3. Gran Reserva
        $catGranReserva = Category::firstOrCreate(
            ['slug' => 'gran-reserva'],
            ['name' => 'Gran Reserva', 'is_active' => true, 'sort_order' => 3]
        );

        // Gran Reserva Ensamblaje 24 Barricas (Cosecha 2020)
        Product::updateOrCreate(
            ['slug' => 'gran-reserva-ensamblaje-24-barricas-2020'],
            [
                'name' => 'Gran Reserva Ensamblaje',
                'subtitle' => '24 Barricas - Cosecha 2020',
                'category_id' => $catGranReserva->id,
                'type' => 'Tinto',
                'price' => 24900,
                'stock' => 50,
                'is_active' => true,
                'vintage_year' => 2020,
                'strain' => '74% Cabernet Sauvignon / 25% Carmenere',
                'origin' => 'Valle del Limarí, Viñedos propios',
                'description' => 'De color rubí profundo, con aroma intenso, complejo y elegante. Prevalecen notas ahumadas con acentuados aromas de mora, cassis, chocolate negro.',
                'image' => 'products/gran-reserva-ensamblaje.jpg',
                'technical_details' => [
                    'analysis' => [
                        'alcohol' => '15,0°',
                        'residual_sugar' => '2,03 g/l',
                        'total_ph' => '3,77',
                        'volatile_acidity' => '1,07 g/l',
                        'total_acidity' => '4,34 g/l',
                    ],
                    'tasting_notes' => 'De color rubí profundo, con aroma intenso, complejo y elegante. Prevalecen notas ahumadas con acentuados aromas de mora, cassis, chocolate negro, pimienta negra, clavo de olor y cardamomo. En el paladar se siente un excelente cuerpo, de taninos amables. Ofrece gran cantidad de sabores de frutos negros, concentrado y jugoso. Termina con buena amplitud y persistencia, dejando un retrogusto de frutos negros. Gran final y persistencia.',
                    'origin_details' => 'Viñedos propios',
                    'harvest_type' => 'Manual (2020)',
                    'wood_type' => '14 a 18 meses en Roble Francés',
                    'presentation' => 'Producción: 7.764 Botellas. Caja 6 botellas cartón.',
                    'awards' => [
                        ['award' => '90 PUNTOS: Guía Peñín 2013 (Gran Reserva Ensamblaje 2010)'],
                        ['award' => '92 PUNTOS: Guía Peñín 2013'],
                    ]
                ]
            ]
        );

        // Gran Reserva Syrah 10 Barricas (Cosecha 2022)
        Product::updateOrCreate(
            ['slug' => 'gran-reserva-syrah-10-barricas-2022'],
            [
                'name' => 'Gran Reserva Syrah',
                'subtitle' => '10 Barricas - Cosecha 2022',
                'category_id' => $catGranReserva->id,
                'type' => 'Tinto',
                'price' => 24900,
                'stock' => 50,
                'is_active' => true,
                'vintage_year' => 2022,
                'strain' => '100% Syrah',
                'origin' => 'Valle del Limarí, Viñedos propios',
                'description' => 'De color rojo oscuro y un aroma complejo, con varias capas aromáticas. Intensamente perfumado a confitura de cerezas negras, rosas secas, incienso y regaliz.',
                'image' => 'products/gran-reserva-syrah.jpg',
                'technical_details' => [
                    'analysis' => [
                        'alcohol' => '15,5°',
                        'residual_sugar' => '2,55 g/l',
                        'total_ph' => '3,72',
                        'volatile_acidity' => '0,64 g/l',
                        'total_acidity' => '3,38 g/l',
                    ],
                    'tasting_notes' => 'De color rojo oscuro y un aroma complejo, con varias capas aromáticas. Intensamente perfumado a confitura de cerezas negras, rosas secas, incienso y regaliz, con notas de humo y un ligero toque de aceite de oliva. En el paladar se siente profundamente concentrado y aterciopelado, de excelente cuerpo, con sabores dulces de mora, grosella, higos secos y pimienta negra, enmarcados por suaves taninos. Final suave, con una impresionante claridad y persistencia.',
                    'origin_details' => 'Viñedos propios',
                    'harvest_type' => 'Manual (2022)',
                    'wood_type' => '14 a 18 meses en Roble Francés',
                    'presentation' => 'Producción: 19.024 Botellas. Caja 6 botellas cartón.',
                    'awards' => [
                        ['award' => '98 PUNTOS: Guía Palacio de Hierro 2013, México'],
                        ['award' => '92 PUNTOS: Guía Peñín 2013'],
                        ['award' => '91 PUNTOS: Stephen Tanzer’s'],
                    ]
                ]
            ]
        );
    }
}
