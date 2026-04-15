<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // IMPORTANT : Utilise Schema::table, pas Schema::create
        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });
    }
};
