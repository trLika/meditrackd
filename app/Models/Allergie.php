<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Allergie extends Model
{
    protected $fillable = [
        'nom',
        'type',
        'description',
        'familles_medicamenteuses',
        'is_actif',
    ];

    protected $casts = [
        'familles_medicamenteuses' => 'array',
        'is_actif' => 'boolean',
    ];

    /**
     * Relation avec les patients allergiques
     */
    public function patientAllergies(): HasMany
    {
        return $this->hasMany(PatientAllergie::class);
    }

    /**
     * Scope pour les allergies actives
     */
    public function scopeActif($query)
    {
        return $query->where('is_actif', true);
    }

    /**
     * Scope par type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Vérifie si cette allergie appartient à une famille médicamenteuse spécifique
     */
    public function appartientAFamille(string $famille): bool
    {
        if (!$this->familles_medicamenteuses) {
            return false;
        }

        return in_array(strtolower($famille), array_map('strtolower', $this->familles_medicamenteuses));
    }

    /**
     * Retourne les familles médicamenteuses pour les réactions croisées
     */
    public function getFamillesCrossReactions(): array
    {
        $familles = [
            'pénicilline' => ['pénicillines', 'amoxicilline', 'ampicilline', 'cloxacilline'],
            'céphalosporines' => ['cefazoline', 'ceftriaxone', 'céfalexine'],
            'sulfamides' => ['sulfamides', 'bactrim', 'cotrimoxazole'],
            'aspirine' => ['aspirine', 'AINS', 'ibuprofène', 'naproxène'],
            'latex' => ['latex', 'caoutchouc naturel'],
        ];

        return $familles[$this->nom] ?? [];
    }
}
