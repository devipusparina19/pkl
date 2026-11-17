<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom yang dibutuhkan fitur profil HANYA jika belum ada,
     * sehingga aman dijalankan pada database yang sudah berisi data.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }

            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username', 30)->nullable()->unique();
            }

            if (! Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->unique();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });
    }
};