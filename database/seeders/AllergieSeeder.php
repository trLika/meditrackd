<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Allergie;

class AllergieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Allergies médicamenteuses les plus courantes
        $allergiesMedicamenteuses = [
            [
                'nom' => 'Pénicilline',
                'type' => 'medicamenteuse',
                'description' => 'Allergie à la pénicilline et ses dérivés',
                'familles_medicamenteuses' => ['pénicillines', 'amoxicilline', 'ampicilline', 'cloxacilline', 'oxacilline'],
            ],
            [
                'nom' => 'Céphalosporines',
                'type' => 'medicamenteuse',
                'description' => 'Allergie aux céphalosporines',
                'familles_medicamenteuses' => ['cefazoline', 'ceftriaxone', 'céfalexine', 'céfuroxime'],
            ],
            [
                'nom' => 'Sulfamides',
                'type' => 'medicamenteuse',
                'description' => 'Allergie aux sulfamides',
                'familles_medicamenteuses' => ['sulfamides', 'bactrim', 'cotrimoxazole', 'sulfasalazine'],
            ],
            [
                'nom' => 'Aspirine',
                'type' => 'medicamenteuse',
                'description' => 'Allergie à l\'aspirine et AINS',
                'familles_medicamenteuses' => ['aspirine', 'AINS', 'ibuprofène', 'naproxène', 'diclofénac'],
            ],
            [
                'nom' => 'Iode',
                'type' => 'medicamenteuse',
                'description' => 'Allergie à l\'iode (produits de contraste)',
                'familles_medicamenteuses' => ['iode', 'produits de contraste iodés'],
            ],
            [
                'nom' => 'Anesthésiques locaux',
                'type' => 'medicamenteuse',
                'description' => 'Allergie aux anesthésiques locaux type lidocaïne',
                'familles_medicamenteuses' => ['lidocaïne', 'xylocaïne', 'bupivacaïne', 'articaine'],
            ],
        ];

        // Allergies alimentaires courantes
        $allergiesAlimentaires = [
            [
                'nom' => 'Arachides',
                'type' => 'alimentaire',
                'description' => 'Allergie aux arachides et produits dérivés',
                'familles_medicamenteuses' => [],
            ],
            [
                'nom' => 'Fruits à coque',
                'type' => 'alimentaire',
                'description' => 'Allergie aux noix, amandes, noisettes...',
                'familles_medicamenteuses' => [],
            ],
            [
                'nom' => 'Crustacés',
                'type' => 'alimentaire',
                'description' => 'Allergie aux crevettes, crabes, homards...',
                'familles_medicamenteuses' => [],
            ],
            [
                'nom' => 'Oeufs',
                'type' => 'alimentaire',
                'description' => 'Allergie aux œufs et produits dérivés',
                'familles_medicamenteuses' => [],
            ],
            [
                'nom' => 'Lait',
                'type' => 'alimentaire',
                'description' => 'Allergie aux protéines du lait de vache',
                'familles_medicamenteuses' => [],
            ],
        ];

        // Allergies environnementales
        $allergiesEnvironnementales = [
            [
                'nom' => 'Latex',
                'type' => 'environnementale',
                'description' => 'Allergie au latex/caoutchouc naturel',
                'familles_medicamenteuses' => ['latex', 'caoutchouc naturel'],
            ],
            [
                'nom' => 'Pollen',
                'type' => 'environnementale',
                'description' => 'Allergie aux pollens (rhume des foins)',
                'familles_medicamenteuses' => [],
            ],
            [
                'nom' => 'Acariens',
                'type' => 'environnementale',
                'description' => 'Allergie aux acariens poussière',
                'familles_medicamenteuses' => [],
            ],
            [
                'nom' => 'Poils animaux',
                'type' => 'environnementale',
                'description' => 'Allergie aux poils de chat, chien...',
                'familles_medicamenteuses' => [],
            ],
        ];

        // Insérer toutes les allergies
        $allAllergies = array_merge($allergiesMedicamenteuses, $allergiesAlimentaires, $allergiesEnvironnementales);

        foreach ($allAllergies as $allergieData) {
            Allergie::create($allergieData);
        }

        $this->command->info('Allergies de base créées avec succès!');
    }
}
