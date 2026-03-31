<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    // Ajoute cette ligne avec les noms exacts de tes colonnes
    protected $fillable = [
        'patient_id',
        'contenu',
        'user_id',
        'date_prescription'
    ];

    // N'oublie pas de définir la relation inverse pour ton rapport
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function user()
{
    // Indique que l'ordonnance appartient à un utilisateur (le médecin)
    return $this->belongsTo(User::class);
}
}
