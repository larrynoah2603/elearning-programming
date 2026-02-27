<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'school_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('school_name')->nullable()->after('subscription_expires_at');
            });
        }

        if (! Schema::hasColumn('users', 'class_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('class_name')->nullable()->after('school_name');
            });
        }

        if (! $this->hasSchoolClassIndex()) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['school_name', 'class_name']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->hasSchoolClassIndex()) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_school_name_class_name_index');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('users', 'class_name')) {
                $columnsToDrop[] = 'class_name';
            }

            if (Schema::hasColumn('users', 'school_name')) {
                $columnsToDrop[] = 'school_name';
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    private function hasSchoolClassIndex(): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `users` WHERE Key_name = 'users_school_name_class_name_index'");

        return count($indexes) > 0;
    }
};
