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
        Schema::create('exercise_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            $table->text('submitted_code');
            $table->enum('status', ['en_cours', 'soumis', 'corrige', 'reussi', 'echoue'])->default('en_cours');
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->integer('attempts')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('corrected_at')->nullable();
            $table->foreignId('corrected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['user_id', 'exercise_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_submissions');
    }
};
