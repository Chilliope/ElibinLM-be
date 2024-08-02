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
        // migrasi profil perpustakaan
        Schema::create('library', function (Blueprint $table) {
            $table->id();
            $table->integer('library_number'); // nomor pokok perpustakaan
            $table->string('library'); // nama perpustakaan
            $table->string('school'); // nama sekolah
            $table->string('address'); // alamat
            $table->string('subdistrict'); // kecamatan
            $table->string('city'); // kabupaten/kota
            $table->string('province'); // provinsi
            $table->integer('post_code'); // kode pos
            $table->integer('phone'); // nomor telpon
            $table->string('website'); // situs web
            $table->string('email'); // email
            $table->string('institutional_status'); // status kelembagaan
            $table->string('since'); // tahun berdiri
            $table->string('land_width'); // luas tanah
            $table->string('building_area'); // luas bangunan
            $table->string('headmaster'); // nama kepala sekolah
            $table->string('head_librarian'); // nama kepala perpustakaan
            $table->string('vision'); // visi
            $table->string('mission'); // misi
            $table->string('short_history'); // sejarah singkat
            $table->string('opening_hours'); // jam buka
            $table->string('library_managers'); // pengelola perpustakaan
            $table->string('library_members'); // anggota perpustakaan
            $table->string('library_activity'); // kegiatan perpustakaan
            $table->timestamps();
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
