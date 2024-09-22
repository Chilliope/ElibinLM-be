<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // migrasi pengunjung perpustakaan
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nama
            $table->unsignedBigInteger('major_id'); // id kelas (table kelas)
            $table->enum('role', [
                'umum',
                'guru',
                'siswa',
                'tenaga kependidikan'
            ]);
            $table->timestamps();

            $table->foreign('major_id')->references('id')->on('majors'); // relasi jurusan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
