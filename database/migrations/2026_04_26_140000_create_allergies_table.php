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
        Schema::create('allergies', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->enum('type', ['medicamenteuse', 'alimentaire', 'environnementale', 'autre'])->default('medicamenteuse');
            $table->text('description')->nullable();
            $table->json('familles_medicamenteuses')->nullable(); // Pour les réactions croisées
            $table->boolean('is_actif')->default(true);
            $table->timestamps();
            
            // Index pour recherche rapide
            $table->index('type');
            $table->index('is_actif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergies');
    }
};
