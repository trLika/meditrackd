<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientAllergie extends Model
{
    protected $fillable = [
        'patient_id',
        'allergie_id',
        'gravite',
        'date_diagnostic',
        'symptomes',
        'notes_medecin',
        'is_active',
        'declared_by',
    ];

    protected $casts = [
        'date_diagnostic' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec le patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relation avec l'allergie
     */
    public function allergie(): BelongsTo
    {
        return $this->belongsTo(Allergie::class);
    }

    /**
     * Relation avec l'utilisateur qui a déclaré l'allergie
     */
    public function declarant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'declared_by');
    }

    /**
     * Scope pour les allergies actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les allergies sévères
     */
    public function scopeSevere($query)
    {
        return $query->whereIn('gravite', ['severe', 'anaphylaxie']);
    }

    /**
     * Vérifie si l'allergie est critique (nécessite une attention immédiate)
     */
    public function isCritique(): bool
    {
        return in_array($this->gravite, ['severe', 'anaphylaxie']) && $this->is_active;
    }

    /**
     * Retourne le libellé de gravité en français
     */
    public function getGraviteLabel(): string
    {
        return match($this->gravite) {
            'legere' => 'Légère',
            'moderee' => 'Modérée',
            'severe' => 'Sévère',
            'anaphylaxie' => 'Anaphylaxie',
            default => 'Inconnue'
        };
    }

    /**
     * Génère une alerte pour les ordonnances
     */
    public function genererAlerte(): array
    {
        return [
            'type' => 'allergie',
            'gravite' => $this->gravite,
            'nom_allergie' => $this->allergie->nom,
            'type_allergie' => $this->allergie->type,
            'symptomes' => $this->symptomes,
            'message_alerte' => "⚠️ ALLERGIE {$this->getGraviteLabel()} : {$this->allergie->nom}",
            'cross_reactions' => $this->allergie->getFamillesCrossReactions()
        ];
    }
}
