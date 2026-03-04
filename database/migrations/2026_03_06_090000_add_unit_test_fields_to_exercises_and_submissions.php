<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            if (!Schema::hasColumn('exercises', 'unit_tests')) {
                $table->json('unit_tests')->nullable()->after('hints');
            }
        });

        Schema::table('exercise_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('exercise_submissions', 'unit_test_results')) {
                $table->json('unit_test_results')->nullable()->after('ai_model');
            }

            if (!Schema::hasColumn('exercise_submissions', 'unit_tests_passed')) {
                $table->unsignedInteger('unit_tests_passed')->default(0)->after('unit_test_results');
            }

            if (!Schema::hasColumn('exercise_submissions', 'unit_tests_total')) {
                $table->unsignedInteger('unit_tests_total')->default(0)->after('unit_tests_passed');
            }

            if (!Schema::hasColumn('exercise_submissions', 'unit_test_score')) {
                $table->unsignedInteger('unit_test_score')->nullable()->after('unit_tests_total');
            }
        });
    }

    public function down(): void
    {
        Schema::table('exercise_submissions', function (Blueprint $table) {
            $table->dropColumn(['unit_test_results', 'unit_tests_passed', 'unit_tests_total', 'unit_test_score']);
        });

        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn('unit_tests');
        });
    }
};
