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
        if (!Schema::hasColumn('users', 'school_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('school_name', 120)->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('users', 'class_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('class_name', 120)->nullable()->after('school_name');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            // Avoid key-length issues on some MySQL/MariaDB utf8mb4 setups.
            if (!$this->indexExists('users', 'users_school_name_index')) {
                $table->index('school_name', 'users_school_name_index');
            }

            if (!$this->indexExists('users', 'users_class_name_index')) {
                $table->index('class_name', 'users_class_name_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if ($this->indexExists('users', 'users_school_name_index')) {
                $table->dropIndex('users_school_name_index');
            }

            if ($this->indexExists('users', 'users_class_name_index')) {
                $table->dropIndex('users_class_name_index');
            }
        });

        if (Schema::hasColumn('users', 'school_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('school_name');
            });
        }

        if (Schema::hasColumn('users', 'class_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('class_name');
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        $database = DB::getDatabaseName();

        $result = DB::selectOne(
            'SELECT COUNT(1) AS aggregate FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$database, $table, $index]
        );

        return (int) ($result->aggregate ?? 0) > 0;
    }
};
