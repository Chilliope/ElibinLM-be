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
        // migrasi table anggota perpustakaan
        Schema::create('library_members', function (Blueprint $table) {
            $table->id();
            $table->integer('NIS')->unique(); // Nomor Induk Siswa 
            $table->string('name'); // nama
            $table->string('place_of_birth'); // tempat lahir
            $table->string('date_of_birth'); // tanggal lahir
            $table->string('phone'); // nomor telepon
            $table->string('address'); // alamat
            $table->unsignedBigInteger('major_id'); // jurusan
            $table->string('image'); // profile picture
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
