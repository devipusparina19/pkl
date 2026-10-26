<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('participants', 'nik')) {
            Schema::table('participants', function (Blueprint $table) {
                $table->string('nik', 30)->nullable()->unique();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('participants', 'nik')) {
            Schema::table('participants', function (Blueprint $table) {
                $table->dropUnique(['nik']);
                $table->dropColumn('nik');
            });
        }
    }
};