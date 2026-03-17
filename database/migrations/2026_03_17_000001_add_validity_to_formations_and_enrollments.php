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
        Schema::table('formations', function (Blueprint $table) {
            $table->unsignedInteger('validity_days')->default(30)->after('price');
        });

        Schema::table('formation_enrollments', function (Blueprint $table) {
            $table->timestamp('access_expires_at')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formation_enrollments', function (Blueprint $table) {
            $table->dropColumn('access_expires_at');
        });

        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn('validity_days');
        });
    }
};
