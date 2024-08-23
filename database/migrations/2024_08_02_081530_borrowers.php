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
        // migrasi peminjam buku perpustakaan
        Schema::create('borrowers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nama
            $table->unsignedBigInteger('class_id'); // id kelas (table kelas)
            $table->unsignedBigInteger('book_id'); // id buku (table buku)
            $table->integer('total'); // jumlah buku
            $table->string('date_of_borrowing'); // tanggal peminjaman
            $table->string('date_of_return'); // tanggal pengembalian
            $table->string('borrowing_code'); // kode peminjaman    
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('class'); // relasi kelas
            $table->foreign('book_id')->references('id')->on('books'); // relasi buku
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
