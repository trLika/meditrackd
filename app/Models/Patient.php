<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['nom', 'prenom', 'sexe', 'telephone', 'adresse', 'groupe_sanguin'];
    // Relation : Un patient a plusieurs consultations
public function consultations()
{
    return $this->hasMany(Consultation::class);
}
}
