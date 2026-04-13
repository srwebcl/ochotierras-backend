<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class UpdateWineInfo extends Command
{
    protected $signature = 'app:update-wine-info';
    protected $description = 'Update wine products with specific technical information provided by the user';

    public function handle()
    {
        $productsData = [
            // 1. Reserva Especial
            [
                'match_name' => 'Reserva Especial Cabernet Sauvignon',
                'data' => [
                    'harvest_year' => 2019,
                    'harvest_type' => 'Manual',
                    'origin' => 'Valle del Limarí, Viñedos propios',
                    'vineyard_location' => 'Viñedo Parcela 44',
                    'aging_potential' => '10 a 12 meses en Roble Francés',
                    'varietal_composition' => '100% Cabernet Sauvignon',
                    'closure_type' => 'Corcho natural',
                    'alcohol' => '13,5°',
                    'residual_sugar' => '3,4 g/l',
                    'total_ph' => '3,79',
                    'volatile_acidity' => '0,83 g/l',
                    'total_acidity' => '3,71 g/l',
                    'tasting_notes' => 'De un brillante y profundo color rubí con una intensa concentración. Contiene un aroma con notas dulces de cassis, frambuesa fresca y menta, con una leve insinuación de nota herbal. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad. En el paladar destacan las frutas negras maduras, cassis y frambuesa, con granito mineral. Pimienta blanca y crema de chocolate, con notas florales persistentes, bien integradas con taninos maduros y elegantes con fina vainilla.',
                    'awards' => [
                        '95 PUNTOS: Guía Palacio de Hierro, México 2013 (Reserva Single Vineyard Cab. Sauv 2011).',
                        '90 PUNTOS: Guía Peñín 2012.',
                        'SILVER: Monde Selection Bruxelles 2008.',
                        'COMMENDED: International Wine Challenge, London 2008.',
                        '90 PUNTOS: Guía Descorchados 2010.',
                    ]
                ]
            ],
            [
                'match_name' => 'Reserva Especial Ensamblaje', // Matches "Cabernet S./Syrah" usually
                'data' => [
                    'harvest_year' => 2022,
                    'harvest_type' => 'Manual',
                    'origin' => 'Valle del Limarí, Viñedos propios',
                    'vineyard_location' => 'Viñedo Parcela 44',
                    'aging_potential' => '10 a 12 meses en Roble Francés',
                    'varietal_composition' => 'Cabernet Sauvignon / Syrah', // Implied from name
                    'closure_type' => 'Corcho natural',
                    'alcohol' => '12,5°',
                    'residual_sugar' => '1,98 g/l',
                    'total_ph' => '3,71',
                    'volatile_acidity' => '0,76 g/l',
                    'total_acidity' => '4,28 g/l',
                    'tasting_notes' => 'De color brillante y profundo color rubí con una intensa concentración. Con aroma a notas dulces de cassis, frambuesa fresca y menta, con una leve insinuación de nota herbal. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad. En el paladar destacan las frutas negras maduras, cassis y frambuesa, con granito mineral. Pimienta blanca y crema de chocolate, bien integradas con taninos maduros y elegantes.',
                    'awards' => [] // None listed for this specific one, keep existing or empty? Assuming keep implies update only fields present.
                ]
            ],

            // 2. Reserva Privada
            [
                'match_name' => 'Reserva Privada Syrah',
                'data' => [
                    'harvest_year' => 2020,
                    'harvest_type' => 'Manual',
                    'origin' => 'Valle del Limarí, Viñedos propios',
                    'vineyard_location' => 'Viñedo Parcela 44 (Suelo franco arenoso, alta mineralidad)',
                    'aging_potential' => '10 a 12 meses en Roble Francés',
                    'closure_type' => 'Corcho natural',
                    'alcohol' => '15,0°',
                    'residual_sugar' => '2,75 g/l',
                    'total_ph' => '3,71',
                    'volatile_acidity' => '0,62 g/l',
                    'total_acidity' => '3,08 g/l',
                    'tasting_notes' => 'Resultado de la cosecha de uvas de un pequeño viñedo caracterizado por suelo franco arenoso y alta mineralidad. Es un vino equilibrado, con notas a frutos rojos, taninos suaves y un color rojo intenso. Se describe como "Una experiencia que no puedes dejar de vivir".',
                    'awards' => [
                        '92 PUNTOS: Guía Peñín 2013 (Reserva Single Vineyard Syrah 2011).',
                        '96 PUNTOS: Guía Palacio de Hierro, 2013 (México).',
                        '90 PUNTOS: Guía Peñín 2012.',
                        '88 PUNTOS: Stephen Tanzer, May 2011.',
                        'SILVER MEDAL: Concours Mondial Bruxelles-Chile 2007.',
                        'SILVER: Grand Catador Hyatt 2008.',
                    ]
                ]
            ],
            [
                'match_name' => 'Reserva Privada Carmenere',
                'data' => [
                    'harvest_year' => 2021,
                    'harvest_type' => 'Manual',
                    'origin' => 'Valle del Limarí, Viñedos propios',
                    // Producción: 10.000 botellas (Might go to description or custom field if not existing)
                    'aging_potential' => '12 meses en Roble Francés',
                    'varietal_composition' => '100% Carmenere',
                    'closure_type' => 'Corcho natural',
                    'alcohol' => '14,5°',
                    'residual_sugar' => '1,81 g/l',
                    'total_ph' => '3,79',
                    'volatile_acidity' => '0,79 g/l',
                    'total_acidity' => '3,76 g/l',
                    'tasting_notes' => 'De color violeta intenso y profundo. En nariz resaltan notas a frutas rojas maduras y un final especiado. En boca muestra notas frescas y dulces con sabor a cereza y tabaco, con un buen equilibrio entre la fruta y el roble francés, y una buena acidez. Presenta taninos suaves y redondos con un buen volumen en boca. Ideal para carnes como cordero y guisos. Servir a 16°C.',
                    'awards' => []
                ]
            ],

            // 3. Gran Reserva
            [
                'match_name' => 'Gran Reserva Ensamblaje', // "24 Barricas"
                'data' => [
                    'harvest_year' => 2020,
                    'harvest_type' => 'Manual',
                    'origin' => 'Valle del Limarí, Viñedos propios',
                    // Producción: 7.764 Botellas
                    'aging_potential' => '14 a 18 meses en Roble Francés',
                    'varietal_composition' => '74% Cabernet Sauvignon / 25% Carmenere',
                    // Tipo de caja: 6 botellas (units_per_box?)
                    'units_per_box' => 6,
                    'alcohol' => '15,0°',
                    'residual_sugar' => '2,03 g/l',
                    'total_ph' => '3,77',
                    'volatile_acidity' => '1,07 g/l',
                    'total_acidity' => '4,34 g/l',
                    'tasting_notes' => 'De color rubí profundo, con aroma intenso, complejo y elegante. Prevalecen notas ahumadas con acentuados aromas de mora, cassis, chocolate negro, pimienta negra, clavo de olor y cardamomo. En el paladar se siente un excelente cuerpo, de taninos amables. Ofrece gran cantidad de sabores de frutos negros, concentrado y jugoso. Termina con buena amplitud y persistencia, dejando un retrogusto de frutos negros. Gran final y persistencia.',
                    'awards' => [
                        '90 PUNTOS: Guía Peñín 2013 (Gran Reserva Ensamblaje 2010).',
                        '92 PUNTOS: Guía Peñín 2013.',
                    ]
                ]
            ],
            [
                'match_name' => 'Gran Reserva Syrah', // "10 Barricas"
                'data' => [
                    'harvest_year' => 2022,
                    'harvest_type' => 'Manual',
                    'origin' => 'Valle del Limarí, Viñedos propios',
                    // Producción: 19.024 Botellas.
                    'aging_potential' => '14 a 18 meses en Roble Francés',
                    'varietal_composition' => '100% Syrah',
                    'units_per_box' => 6,
                    'alcohol' => '15,5°',
                    'residual_sugar' => '2,55 g/l',
                    'total_ph' => '3,72',
                    'volatile_acidity' => '0,64 g/l',
                    'total_acidity' => '3,38 g/l',
                    'tasting_notes' => 'De color rojo oscuro y un aroma complejo, con varias capas aromáticas. Intensamente perfumado a confitura de cerezas negras, rosas secas, incienso y regaliz, con notas de humo y un ligero toque de aceite de oliva. En el paladar se siente profundamente concentrado y aterciopelado, de excelente cuerpo, con sabores dulces de mora, grosella, higos secos y pimienta negra, enmarcados por suaves taninos. Final suave, con una impresionante claridad y persistencia.',
                    'awards' => [
                        '98 PUNTOS: Guía Palacio de Hierro 2013, México (Gran Reserva Syrah 2009).',
                        '92 PUNTOS: Guía Peñín 2013 (Gran Reserva Syrah 2013).',
                        '91 PUNTOS: Stephen Tanzer’s, por Josh Raynolds, New York, Mayo.',
                    ]
                ]
            ],
        ];

        foreach ($productsData as $item) {
            $product = Product::where('name', 'like', '%' . $item['match_name'] . '%')->first();

            if ($product) {
                $this->info("Updating found product: {$product->name}");

                // Update basic fields
                $mappedData = $item['data'];
                if (isset($mappedData['awards'])) {
                    // Start with existing awards or empty array, but usually we just replace if explicit
                    // The user provided a specific list, so we replace.
                    if (empty($mappedData['awards'])) {
                        unset($mappedData['awards']); // Don't overwrite if empty in my map
                    }
                }

                $product->fill($mappedData);
                $product->save();
                $this->info("Updated {$product->name} successfully.");
            } else {
                $this->warn("Could not find product matching: " . $item['match_name']);
            }
        }

        $this->info('Updates completed.');
    }
}
