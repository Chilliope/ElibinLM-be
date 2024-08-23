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
        // migrasi table koleksi buku
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique(); // judul buku
            $table->string('slug');
            $table->string('writer'); // penulis
            $table->string('publisher'); // penerbit
            $table->bigInteger('ISBN'); // International Standard Book Number
            $table->string('publication_year'); // tahun terbit
            $table->string('book_spine_number'); // nomor punggung buku
            $table->integer('page_size'); // jumlah halaman
            $table->string('information'); // keterangan
            $table->string('image'); // foto buku
            $table->integer('stock'); // stok buku
            $table->unsignedBigInteger('rack_id'); // rak buku
            $table->timestamps();

            $table->foreign('rack_id')->references('id')->on('racks'); // relasi rak
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
