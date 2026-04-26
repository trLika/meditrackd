<?php

namespace App\Services;

class MedicationSafetyService
{
    /**
     * Mapping des familles de médicaments et de leurs membres/noms commerciaux courants.
     * Basé sur les familles d'allergies les plus fréquentes.
     */
    protected static $crossReactions = [
        'pénicilline' => [
            'amoxicilline', 'augmentin', 'clamoxyl', 'extencilline', 'penicilline', 'oracilline', 'bristopen'
        ],
        'ains' => [
            'ibuprofène', 'advil', 'nurofen', 'diclofénac', 'voltarene', 'aspirine', 'kétoprofène', 'bi-profénid', 'naproxène'
        ],
        'sulfamide' => [
            'bactrim', 'adiazine', 'flammazine'
        ],
        'paracétamol' => [
            'doliprane', 'efferalgan', 'dafalgan', 'paracetamol'
        ],
        'codéine' => [
            'dafalgan codéiné', 'codoliprane', 'klipal'
        ]
    ];

    /**
     * Mapping des contre-indications liées aux antécédents médicaux (Drug-Disease Interactions).
     */
    protected static $contraindications = [
        'glaucome' => [
            'pseudoéphédrine', 'humex', 'actifed', 'fervex', 'rhumagrip', 'dolirhume', 'atropine'
        ],
        'ulcère' => [
            'ibuprofène', 'advil', 'nurofen', 'diclofénac', 'voltarene', 'aspirine', 'kétoprofène', 'ains'
        ],
        'asthme' => [
            'aspirine', 'ibuprofène', 'ains', 'diclofénac', 'voltarene', 'advil', 'bêta-bloquant', 'propranolol', 'aténolol', 'bisoprolol'
        ],
        'diabète' => [
            'corticoïde', 'cortisone', 'prednisone', 'solupred', 'dexaméthasone', 'betaméthasone', 'sirop'
        ],
        'drépanocytose' => [
            'pseudoéphédrine', 'actifed', 'humex', 'fervex', 'rhumagrip'
        ],
        'anémie' => [
            'aspirine', 'ains', 'ibuprofène' // Risque occulte de saignement
        ],
        'hypertension' => [
            'pseudoéphédrine', 'actifed', 'rhumagrip'
        ],
        'insuffisance rénale' => [
            'ains', 'ibuprofène', 'diclofénac', 'voltarene'
        ]
    ];

    /**
     * Détecte si un contenu d'ordonnance présente un risque par rapport aux allergies OU antécédents.
     */
    public static function checkConflicts(string $prescriptionContent, ?string $patientAllergies, ?string $patientAntecedents = ''): ?array
    {
        $content = strtolower($prescriptionContent);
        $allergies = strtolower($patientAllergies ?? '');
        $antecedents = strtolower($patientAntecedents ?? '');

        // 1. VÉRIFICATION DES ALLERGIES (Logique existante)
        if (!empty($allergies) && $allergies !== 'aucune allergie signalée') {
            $listeAllergiesPatient = array_filter(preg_split('/[,\s]+/', $allergies), function($w) { return strlen($w) > 3; });
            foreach ($listeAllergiesPatient as $allergie) {
                if (str_contains($content, $allergie)) {
                    return ['type' => 'Allergie Directe', 'allergy' => strtoupper($allergie), 'match' => strtoupper($allergie)];
                }
                foreach (self::$crossReactions as $famille => $membres) {
                    if ($allergie === $famille || str_contains($famille, $allergie)) {
                        foreach ($membres as $membre) {
                            if (str_contains($content, $membre)) {
                                return ['type' => 'Allergie Croisée (Famille)', 'allergy' => strtoupper($famille), 'match' => strtoupper($membre)];
                            }
                        }
                    }
                    if (in_array($allergie, $membres)) {
                        foreach ($membres as $membre) {
                            if (str_contains($content, $membre)) {
                                return ['type' => 'Même famille d\'allergie', 'allergy' => strtoupper($allergie), 'match' => strtoupper($membre)];
                            }
                        }
                    }
                }
            }
        }

        // 2. VÉRIFICATION DES CONTRE-INDICATIONS (Antécédents)
        if (!empty($antecedents)) {
            foreach (self::$contraindications as $pathologie => $interdits) {
                if (str_contains($antecedents, $pathologie)) {
                    foreach ($interdits as $interdit) {
                        if (str_contains($content, $interdit)) {
                            return [
                                'type' => 'CONTRE-INDICATION MÉDICALE',
                                'allergy' => strtoupper($pathologie),
                                'match' => strtoupper($interdit),
                                'info' => "Ce médicament est contre-indiqué en cas de " . strtoupper($pathologie)
                            ];
                        }
                    }
                }
            }
        }

        return null;
    }

    public static function getCrossReactionsMap(): array
    {
        return [
            'crossReactions' => self::$crossReactions,
            'contraindications' => self::$contraindications
        ];
    }
}
