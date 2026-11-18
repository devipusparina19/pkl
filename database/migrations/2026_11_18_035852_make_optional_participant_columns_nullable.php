<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Hanya ubah kolom yang memang ada di tabel Anda.
            foreach (['nip', 'rank', 'profession', 'phone'] as $column) {
                if (Schema::hasColumn('participants', $column)) {
                    $table->string($column)->nullable()->default(null)->change();
                }
            }
        });
    }

    public function down(): void
    {
        // Sengaja kosong: mengembalikan NOT NULL akan gagal jika sudah ada data NULL.
    }
};