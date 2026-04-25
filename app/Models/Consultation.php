<?php

namespace App\Models;

// 1. On importe l'outil de l'extérieur
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    // 2. On l'active à l'intérieur de la classe
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'date_consultation',
        'symptomes',
        'diagnostic',
        'traitement',
        'poids',
        'tension',
        'created_at',
        'updated_at'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_consultation' => 'date',
            'symptomes' => 'encrypted',
            'diagnostic' => 'encrypted',
            'traitement' => 'encrypted',
            'poids' => 'float',
        ];
    }
}
