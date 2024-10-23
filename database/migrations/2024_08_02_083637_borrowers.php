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
            $table->unsignedBigInteger('member_id');
            // $table->unsignedBigInteger('book_id'); // id buku (table buku)
            $table->string('book_title');
            $table->integer('total'); // jumlah buku
            $table->string('date_of_borrowing'); // tanggal peminjaman
            $table->string('date_of_return'); // tanggal pengembalian
            $table->string('borrowing_code'); // kode peminjaman    
            $table->enum('status', [
                'dipinjam',
                'dikembalikan'
            ])->default('dipinjam');
            $table->timestamps();

            // $table->foreign('book_id')->references('id')->on('books'); // relasi buku
            $table->foreign('member_id')->references('id')->on('library_members'); // relasi anggota
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
