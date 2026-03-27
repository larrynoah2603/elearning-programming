<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('forum_tags')->insert([
            ['name' => 'Python', 'slug' => 'python', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Débutant', 'slug' => 'debutant', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bug', 'slug' => 'bug', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::create('forum_thread_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_thread_id')->constrained('forum_threads')->cascadeOnDelete();
            $table->foreignId('forum_tag_id')->constrained('forum_tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['forum_thread_id', 'forum_tag_id']);
        });

        Schema::table('forum_replies', function (Blueprint $table) {
            $table->boolean('is_accepted')->default(false)->after('body');
        });
    }

    public function down(): void
    {
        Schema::table('forum_replies', function (Blueprint $table) {
            $table->dropColumn('is_accepted');
        });

        Schema::dropIfExists('forum_thread_tag');
        Schema::dropIfExists('forum_tags');
    }
};
