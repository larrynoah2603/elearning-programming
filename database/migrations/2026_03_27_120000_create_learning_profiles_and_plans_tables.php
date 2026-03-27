<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_learning_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->string('goal');
            $table->unsignedSmallInteger('minutes_per_day')->default(30);
            $table->json('preferred_languages')->nullable();
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('learning_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('starts_at');
            $table->date('ends_at');
            $table->enum('status', ['active', 'completed', 'paused'])->default('active');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('learning_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_plan_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['lesson', 'exercise', 'quiz', 'formation_module']);
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('title');
            $table->string('url')->nullable();
            $table->unsignedSmallInteger('estimated_minutes')->default(20);
            $table->unsignedSmallInteger('position')->default(1);
            $table->boolean('is_done')->default(false);
            $table->timestamp('done_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_plan_items');
        Schema::dropIfExists('learning_plans');
        Schema::dropIfExists('user_learning_profiles');
    }
};
