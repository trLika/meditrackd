<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // On crée la table 'services' en premier
        Schema::create('services', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); // Nom du service (ex: Cardiologie)
            $table->text('description')->nullable(); // Description optionnelle
            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
