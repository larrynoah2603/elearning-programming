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
        Schema::table('exercise_submissions', function (Blueprint $table) {
            $table->integer('ai_score')->nullable()->after('score');
            $table->text('ai_feedback')->nullable()->after('feedback');
            $table->boolean('ai_requires_human_review')->default(false)->after('ai_feedback');
            $table->timestamp('ai_corrected_at')->nullable()->after('corrected_at');
            $table->string('ai_model')->nullable()->after('ai_corrected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercise_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'ai_score',
                'ai_feedback',
                'ai_requires_human_review',
                'ai_corrected_at',
                'ai_model',
            ]);
        });
    }
};

