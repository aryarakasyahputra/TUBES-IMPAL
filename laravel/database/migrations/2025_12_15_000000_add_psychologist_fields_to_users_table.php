<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_psychologist')->default(false)->after('is_suspended');
            $table->string('psy_status')->default('none')->after('is_psychologist'); // none | pending | approved | rejected
            $table->string('str_path')->nullable()->after('psy_status');
            $table->string('ijazah_path')->nullable()->after('str_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_psychologist', 'psy_status', 'str_path', 'ijazah_path']);
        });
    }
};
