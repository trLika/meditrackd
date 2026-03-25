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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            // Clé étrangère vers la table patients
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');

            $table->date('date_consultation');
            $table->text('symptomes')->nullable();
            $table->text('diagnostic');
            $table->text('traitement');
            $table->float('poids')->nullable();
            $table->string('tension')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
