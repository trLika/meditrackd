<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
   protected $fillable = [
    'nom',
    'prenom',
    'sexe',
    'telephone',
    'adresse',
    'groupe_sanguin',
    'antecedents',
    'is_critique'  ,
];
public function consultations()
{
    return $this->hasMany(Consultation::class);
}
}
