<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exercise_submissions', function (Blueprint $table) {
            $table->json('feedback_structured')->nullable()->after('feedback');
            $table->unsignedSmallInteger('hint_penalty')->default(0)->after('feedback_structured');
        });

        Schema::create('exercise_hints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('level');
            $table->text('content');
            $table->timestamps();

            $table->unique(['exercise_id', 'level']);
        });

        Schema::create('exercise_hint_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('hint_level');
            $table->timestamp('viewed_at');

            $table->unique(['user_id', 'exercise_id', 'hint_level'], 'exercise_hint_views_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercise_hint_views');
        Schema::dropIfExists('exercise_hints');

        Schema::table('exercise_submissions', function (Blueprint $table) {
            $table->dropColumn(['feedback_structured', 'hint_penalty']);
        });
    }
};
