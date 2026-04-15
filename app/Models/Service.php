<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    // Indispensable pour permettre l'insertion de ces colonnes
    protected $fillable = [
        'name',
        'description'
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function users() {
    return $this->belongsToMany(User::class, 'service_user'); // Un service a plusieurs médecins
}


}
