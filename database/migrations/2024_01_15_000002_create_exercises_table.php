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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique();
            $table->enum('difficulty', ['simple', 'complexe'])->default('simple');
            $table->enum('access_level', ['free', 'subscribed'])->default('free');
            $table->enum('programming_language', ['python', 'javascript', 'java', 'cpp', 'php', 'html_css', 'sql'])->default('python');
            $table->text('instructions');
            $table->text('starter_code')->nullable();
            $table->text('solution_code')->nullable();
            $table->text('hints')->nullable();
            $table->integer('points')->default(10);
            $table->integer('estimated_time')->nullable(); // in minutes
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
