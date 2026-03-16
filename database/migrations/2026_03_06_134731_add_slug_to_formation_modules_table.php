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
        // Vérifier si la colonne slug n'existe pas déjà
        if (!Schema::hasColumn('formation_modules', 'slug')) {
            Schema::table('formation_modules', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formation_modules', function (Blueprint $table) {
            if (Schema::hasColumn('formation_modules', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
