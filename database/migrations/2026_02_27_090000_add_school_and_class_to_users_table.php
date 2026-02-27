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
        Schema::table('users', function (Blueprint $table) {
            $table->string('school_name', 120)->nullable()->after('name');
            $table->string('class_name', 120)->nullable()->after('school_name');
            $table->index(['school_name', 'class_name'], 'users_school_name_class_name_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_school_name_class_name_index');
            $table->dropColumn(['school_name', 'class_name']);
        });
    }
};
