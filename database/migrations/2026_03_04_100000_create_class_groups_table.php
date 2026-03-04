<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('school_name', 120)->nullable();
            $table->string('class_name', 120)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('class_group_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_group_id')->constrained('class_groups')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['class_group_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_group_student');
        Schema::dropIfExists('class_groups');
    }
};
