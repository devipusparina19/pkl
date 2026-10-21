<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            if (!Schema::hasColumn('participants', 'nik')) {
                $table->string('nik', 30)->nullable()->unique();
            }

            if (!Schema::hasColumn('participants', 'gender')) {
                $table->string('gender', 30)->nullable();
            }

            if (!Schema::hasColumn('participants', 'rank')) {
                $table->string('rank', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        // Sengaja tidak menghapus kolom agar data tidak hilang.
    }
};