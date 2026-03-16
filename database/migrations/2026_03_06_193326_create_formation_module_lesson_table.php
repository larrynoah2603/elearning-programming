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
        Schema::create('formation_module_lesson', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_module_id')->constrained('formation_modules')->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['formation_module_id', 'lesson_id']);
        });

        Schema::create('formation_module_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_module_id')->constrained('formation_modules')->onDelete('cascade');
            $table->foreignId('video_id')->constrained('videos')->onDelete('cascade');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['formation_module_id', 'video_id']);
        });

        Schema::create('formation_module_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_module_id')->constrained('formation_modules')->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained('exercises')->onDelete('cascade');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['formation_module_id', 'exercise_id']);
        });

        Schema::create('formation_user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('formation_id')->constrained('formations')->onDelete('cascade');
            $table->foreignId('formation_module_id')->constrained('formation_modules')->onDelete('cascade');
            $table->unsignedInteger('progress_percentage')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'formation_module_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_user_progress');
        Schema::dropIfExists('formation_module_exercise');
        Schema::dropIfExists('formation_module_video');
        Schema::dropIfExists('formation_module_lesson');
    }
};

