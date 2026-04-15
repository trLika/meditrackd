<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;//bibliotheque de date laravel

class Patient extends Model
{
   protected $fillable = [
    'nom',
    'prenom',
    'sexe',
    'date_naissance',
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
public function getAgeAttribute()//fonction pour calculer l'age
{
    if(!$this->date_naissance){
        return '-';
    }
    return Carbon::parse($this->date_naissance)->age;
}
// Dans Patient.php
public function ordonnances()
{

    return $this->hasMany(Ordonnance::class);
}

public function service() {
    return $this->belongsTo(Service::class);
}
}
