<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedReservaEspecial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-reserva-especial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding Reserva Especial Cabernet Sauvignon...');

        $data = [
            'name' => 'Reserva Especial Cabernet Sauvignon',
            'slug' => 'reserva-especial-cabernet-sauvignon',
            'type' => 'Tinto',
            'subtitle' => 'Valle del Limarí',
            'price' => 45000, // Estimado caja x 6
            'stock' => 50,
            'description' => "De un brillante y profundo color rubí con una intensa concentración. Un vino concentrado, mineral y elegante.",
            'image' => 'products/reserva-especial-cs.webp', // We will assume we copy the file here or link it
            'vintage_year' => 2019,
            'strain' => 'Cabernet Sauvignon',
            'origin' => 'Valle del Limarí',
            'units_per_box' => 6,
            'is_pack' => false,
            'technical_details' => [
                'harvest_type' => 'Manual',
                'origin_details' => 'Viñedo Parcela 44, 30° 38’ 42’’ S | 71° 07’ 45’’ W',
                'presentation' => 'Botella de vidrio de 750 cc.',
                'closure_type' => 'Corcho natural',
                'composition' => '100% Cabernet Sauvignon',
                'aging_potential' => '10 a 12 meses', // Note: User said "Tiempo de guarda", likely means aging in barrel
                'wood_type' => 'Roble Francés',
                'analysis' => [
                    'alcohol' => '13,5°',
                    'residual_sugar' => '3,4 g/l',
                    'total_ph' => '3,79',
                    'volatile_acidity' => '0,83 g/l',
                    'total_acidity' => '3,71 g/l'
                ],
                'tasting_notes' => "De un brillante y profundo color rubí con una intensa concentración. Contiene un aroma con notas dulces de cassis, frambuesa fresca y menta, con una leve insinuación de nota herbal. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad.\n\nEn el Paladar destacan las frutas negra madura, cassis y frambuesa, con granito mineral. Pimienta blanca y crema de chocolate, con notas florales persistentes, bien integradas con taninos maduros y elegantes con fina vainilla, acidez equilibrada y final largo. Un vino concentrado, mineral y elegante.",
                'awards' => [
                    ['award' => '95 PUNTOS: Guía Palacio de Hierro, México 2013'],
                    ['award' => '90 PUNTOS: Guía Peñín 2012'],
                    ['award' => 'SILVER: Monde Selection Bruxelles 2008'],
                    ['award' => 'COMMENDED: International Wine Challenge, London, 2008'],
                    ['award' => '90 PUNTOS: Guía Descorchados 2010'],
                ]
            ]
        ];

        $product = \App\Models\Product::updateOrCreate(
            ['slug' => $data['slug']],
            $data
        );

        // Copy image if exists in source
        $sourcePath = '/Users/sebastianrodriguezmilla/proyectos-web/ochotierras/public/images/bottles/Reserva Especial CS.webp';
        $destPath = storage_path('app/public/products/reserva-especial-cs.webp');

        if (file_exists($sourcePath)) {
            if (!is_dir(dirname($destPath))) {
                mkdir(dirname($destPath), 0755, true);
            }
            copy($sourcePath, $destPath);
            $this->info('Image copied successfully.');
        } else {
            $this->warn("Source image not found at: $sourcePath");
        }

        $this->info('Product seeded successfully!');
    }
}
