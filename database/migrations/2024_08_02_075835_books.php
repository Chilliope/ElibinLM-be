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
            $table->string('publication_year'); // tahun terbit
            $table->string('isbn')->unique(); // isbn
            $table->integer('page_size'); // jumlah halaman
            $table->string('information'); // keterangan
            $table->string('image'); // foto buku
            $table->unsignedBigInteger('rack_id')->nullable(); // rak buku
            $table->unsignedBigInteger('subject_id')->default(1)->nullable();
            $table->timestamps();

            $table->foreign('rack_id')->references('id')->on('racks'); // relasi rak
            $table->foreign('subject_id')->references('id')->on('subjects'); // relasi subjek
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
