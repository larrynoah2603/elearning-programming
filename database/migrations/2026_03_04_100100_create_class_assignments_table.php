<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_group_id')->constrained('class_groups')->cascadeOnDelete();
            $table->enum('content_type', ['exercise', 'lesson']);
            $table->unsignedBigInteger('content_id');
            $table->string('title', 180);
            $table->text('instructions')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamps();

            $table->index(['content_type', 'content_id']);
            $table->index('due_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_assignments');
    }
};
