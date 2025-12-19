<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['anonim', 'psikolog'])->default('anonim')->after('email');
            }
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'str_file')) {
                $table->string('str_file')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('users', 'ijazah_file')) {
                $table->string('ijazah_file')->nullable()->after('str_file');
            }
        });

        // Copy from existing columns if present
        if (Schema::hasColumn('users', 'str_path')) {
            DB::table('users')->whereNull('str_file')->update(['str_file' => DB::raw('str_path')]);
        }
        if (Schema::hasColumn('users', 'ijazah_path')) {
            DB::table('users')->whereNull('ijazah_file')->update(['ijazah_file' => DB::raw('ijazah_path')]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) $table->dropColumn('role');
            if (Schema::hasColumn('users', 'is_verified')) $table->dropColumn('is_verified');
            if (Schema::hasColumn('users', 'str_file')) $table->dropColumn('str_file');
            if (Schema::hasColumn('users', 'ijazah_file')) $table->dropColumn('ijazah_file');
        });
    }
};
