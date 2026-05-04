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
        Schema::create('patient_allergies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('allergie_id')->constrained()->onDelete('cascade');
            $table->enum('gravite', ['legere', 'moderee', 'severe', 'anaphylaxie'])->default('moderee');
            $table->date('date_diagnostic')->nullable();
            $table->text('symptomes')->nullable(); // Description des symptômes observés
            $table->text('notes_medecin')->nullable(); // Notes du médecin
            $table->boolean('is_active')->default(true); // Si l'allergie est toujours active
            $table->foreignId('declared_by')->nullable()->constrained('users')->onDelete('set null'); // Qui a déclaré l'allergie
            $table->timestamps();
            
            // Index pour performances
            $table->index(['patient_id', 'is_active']);
            $table->index('gravite');
            $table->index('date_diagnostic');
            
            // Un patient ne peut avoir qu'une fois la même allergie active
            $table->unique(['patient_id', 'allergie_id', 'is_active'], 'unique_active_allergie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_allergies');
    }
};
