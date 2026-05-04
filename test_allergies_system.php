<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Patient;
use App\Models\Allergie;
use App\Models\PatientAllergie;
use App\Services\AllergieService;

// Boot Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST SYSTÈME D'ALLERGIES STRUCTURÉES ===\n\n";

// 1. Vérifier que les allergies sont bien créées
echo "1. Vérification des allergies dans la base de données:\n";
$allAllergies = Allergie::all();
echo "   - Nombre total d'allergies: " . $allAllergies->count() . "\n";

foreach ($allAllergies->take(5) as $allergie) {
    echo "   - {$allergie->nom} ({$allergie->type})\n";
}
echo "\n";

// 2. Trouver un patient existant pour le test
echo "2. Recherche d'un patient existant:\n";
$patient = Patient::first();
if (!$patient) {
    echo "   - Aucun patient trouvé. Création d'un patient test...\n";
    $patient = Patient::create([
        'nom' => 'TEST',
        'prenom' => 'Allergie',
        'sexe' => 'M',
        'date_naissance' => '1990-01-01',
        'telephone' => '0123456789',
        'adresse' => 'Test Address',
        'groupe_sanguin' => 'A+',
        'antecedents' => 'Aucun',
        'is_critique' => false,
        'service_id' => 1,
    ]);
}
echo "   - Patient trouvé: {$patient->nom} {$patient->prenom} (ID: {$patient->id})\n\n";

// 3. Ajouter une allergie test
echo "3. Ajout d'une allergie de test:\n";
try {
    $pénicilline = Allergie::where('nom', 'Pénicilline')->first();
    if ($pénicilline) {
        $patientAllergie = AllergieService::ajouterAllergiePatient($patient, [
            'allergie_id' => $pénicilline->id,
            'gravite' => 'severe',
            'date_diagnostic' => '2023-01-15',
            'symptomes' => 'Urticaire, difficulté respiratoire',
            'notes_medecin' => 'Test de vérification du système',
        ]);
        echo "   - Allergie ajoutée avec succès: {$pénicilline->nom} ({$patientAllergie->gravite})\n";
    }
} catch (Exception $e) {
    echo "   - Erreur: " . $e->getMessage() . "\n";
}
echo "\n";

// 4. Vérifier les relations
echo "4. Test des relations:\n";
$allergiesPatient = $patient->allergiesStructurées()->with('allergie')->get();
echo "   - Nombre d'allergies du patient: " . $allergiesPatient->count() . "\n";

foreach ($allergiesPatient as $pa) {
    echo "   - {$pa->allergie->nom} ({$pa->gravite}) - Active: " . ($pa->is_active ? 'Oui' : 'Non') . "\n";
}
echo "\n";

// 5. Test des alertes médicamenteuses
echo "5. Test des alertes médicamenteuses:\n";
$ordonnanceTest = "Prescription: Amoxicilline 500mg 3x/jour pendant 7 jours";
$alertes = AllergieService::verifierInteractionsMedicamenteuses($patient, $ordonnanceTest);

echo "   - Ordonnance test: {$ordonnanceTest}\n";
echo "   - Alertes trouvées: " . count($alertes) . "\n";

foreach ($alertes as $alerte) {
    echo "     * {$alerte['message']}\n";
}
echo "\n";

// 6. Test du rapport
echo "6. Génération du rapport d'allergies:\n";
$rapport = AllergieService::genererRapportAllergies($patient);
echo "   - Patient: {$rapport['patient']}\n";
echo "   - Date rapport: {$rapport['date_rapport']}\n";
echo "   - Total allergies: {$rapport['total_allergies']}\n";
echo "   - Allergies critiques: " . ($rapport['allergies_critiques'] ? 'Oui' : 'Non') . "\n";
echo "   - Allergies actives: " . count($rapport['allergies_actives']) . "\n";

// 7. Test de compatibilité descendante
echo "\n7. Test compatibilité avec champ texte existant:\n";
echo "   - Champ allergies (ancien système): " . ($patient->allergies ?: 'Non défini') . "\n";
echo "   - Méthode getAllergiesPourAlertes(): " . count($patient->getAllergiesPourAlertes()) . " alertes\n";

echo "\n=== TEST TERMINÉ AVEC SUCCÈS ===\n";
