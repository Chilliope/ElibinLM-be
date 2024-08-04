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
        // migrasi kelas 
        Schema::create('class', function (Blueprint $table) {
            $table->id();
            $table->enum('class', [
                'X',
                'XI',
                'XII',
                'XIII',
                'Guru'
            ]); // kelas
            $table->unsignedBigInteger('major_id'); // id jurusan (table jurusan)
            $table->enum('alphabet', range('A', 'Z')); // alfabet kelas
            $table->string('class_fix')->unique();
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
