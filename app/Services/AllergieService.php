<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Allergie;
use App\Models\PatientAllergie;
use Illuminate\Support\Facades\Auth;

class AllergieService
{
    /**
     * Ajoute une allergie structurée à un patient
     */
    public static function ajouterAllergiePatient(Patient $patient, array $data): PatientAllergie
    {
        // Vérifier que l'allergie n'existe pas déjà activement
        $existing = $patient->allergiesStructurées()
            ->where('allergie_id', $data['allergie_id'])
            ->where('is_active', true)
            ->first();

        if ($existing) {
            throw new \Exception('Cette allergie est déjà enregistrée pour ce patient');
        }

        return $patient->allergiesStructurées()->create([
            'allergie_id' => $data['allergie_id'],
            'gravite' => $data['gravite'] ?? 'moderee',
            'date_diagnostic' => $data['date_diagnostic'] ?? now(),
            'symptomes' => $data['symptomes'] ?? null,
            'notes_medecin' => $data['notes_medecin'] ?? null,
            'declared_by' => Auth::id(),
        ]);
    }

    /**
     * Désactive une allergie pour un patient
     */
    public static function desactiverAllergiePatient(Patient $patient, int $allergieId): bool
    {
        $patientAllergie = $patient->allergiesStructurées()
            ->where('allergie_id', $allergieId)
            ->where('is_active', true)
            ->first();

        if (!$patientAllergie) {
            return false;
        }

        $patientAllergie->update(['is_active' => false]);
        return true;
    }

    /**
     * Vérifie les interactions médicamenteuses avec les allergies du patient
     */
    public static function verifierInteractionsMedicamenteuses(Patient $patient, string $contenuOrdonnance): array
    {
        $alertes = [];
        $contenu = strtolower($contenuOrdonnance);

        // Utiliser le nouveau système structuré si disponible
        if ($patient->allergiesStructurées()->count() > 0) {
            foreach ($patient->allergiesActives as $patientAllergie) {
                $alerte = self::verifierAllergieMedicament($patientAllergie, $contenu);
                if ($alerte) {
                    $alertes[] = $alerte;
                }
            }
        } else {
            // Fallback sur le système texte existant
            if (!empty($patient->allergies) && $patient->allergies !== 'Aucune allergie signalée') {
                $alerte = self::verifierAllergieTexte($patient->allergies, $contenu);
                if ($alerte) {
                    $alertes[] = $alerte;
                }
            }
        }

        return $alertes;
    }

    /**
     * Vérifie une allergie spécifique contre un contenu d'ordonnance
     */
    private static function verifierAllergieMedicament(PatientAllergie $patientAllergie, string $contenu): ?array
    {
        $allergie = $patientAllergie->allergie;
        $nomAllergie = strtolower($allergie->nom);

        // Vérification directe
        if (str_contains($contenu, $nomAllergie)) {
            return [
                'type' => 'direct',
                'gravite' => $patientAllergie->gravite,
                'allergie' => $allergie->nom,
                'message' => "⚠️ ALLERGIE DIRECTE : {$allergie->nom} ({$patientAllergie->getGraviteLabel()})",
                'patient_allergie_id' => $patientAllergie->id,
            ];
        }

        // Vérification des réactions croisées
        if ($allergie->familles_medicamenteuses) {
            foreach ($allergie->familles_medicamenteuses as $famille) {
                if (str_contains($contenu, strtolower($famille))) {
                    return [
                        'type' => 'cross_reaction',
                        'gravite' => $patientAllergie->gravite,
                        'allergie' => $allergie->nom,
                        'famille' => $famille,
                        'message' => "⚠️ RÉACTION CROISÉE : {$famille} (allergie {$allergie->nom})",
                        'patient_allergie_id' => $patientAllergie->id,
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Vérification fallback avec le champ texte
     */
    private static function verifierAllergieTexte(string $allergiesTexte, string $contenu): ?array
    {
        $listeAllergies = array_filter(
            preg_split('/[,\s]+/', strtolower($allergiesTexte)),
            function($word) { return strlen($word) > 3; }
        );

        foreach ($listeAllergies as $allergie) {
            if (str_contains($contenu, $allergie)) {
                return [
                    'type' => 'direct',
                    'gravite' => 'inconnue',
                    'allergie' => $allergie,
                    'message' => "⚠️ ALLERGIE : {$allergie}",
                    'patient_allergie_id' => null,
                ];
            }
        }

        return null;
    }

    /**
     * Génère un rapport d'allergies pour un patient
     */
    public static function genererRapportAllergies(Patient $patient): array
    {
        $rapport = [
            'patient' => $patient->nom . ' ' . $patient->prenom,
            'date_rapport' => now()->format('d/m/Y H:i'),
            'allergies_actives' => [],
            'allergies_inactives' => [],
            'allergies_critiques' => false,
            'total_allergies' => 0,
        ];

        foreach ($patient->allergiesStructurées as $patientAllergie) {
            $allergieData = [
                'id' => $patientAllergie->id,
                'nom' => $patientAllergie->allergie->nom,
                'type' => $patientAllergie->allergie->type,
                'gravite' => $patientAllergie->gravite,
                'gravite_label' => $patientAllergie->getGraviteLabel(),
                'date_diagnostic' => $patientAllergie->date_diagnostic?->format('d/m/Y'),
                'symptomes' => $patientAllergie->symptomes,
                'notes_medecin' => $patientAllergie->notes_medecin,
                'declared_by' => $patientAllergie->declarant?->name,
                'created_at' => $patientAllergie->created_at->format('d/m/Y H:i'),
            ];

            if ($patientAllergie->is_active) {
                $rapport['allergies_actives'][] = $allergieData;
                if ($patientAllergie->isCritique()) {
                    $rapport['allergies_critiques'] = true;
                }
            } else {
                $rapport['allergies_inactives'][] = $allergieData;
            }
            
            $rapport['total_allergies']++;
        }

        // Ajouter les allergies texte si aucune allergie structurée
        if ($rapport['total_allergies'] === 0 && !empty($patient->allergies)) {
            $rapport['allergies_texte'] = $patient->allergies;
        }

        return $rapport;
    }
}
