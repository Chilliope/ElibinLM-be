<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majors = [
            'PPLG',    // Pengembangan Perangkat Lunak dan Gim
            'TKJ',     // Teknik Komputer dan Jaringan
            'RPL',     // Rekayasa Perangkat Lunak
            'MM',      // Multimedia
            'TAV',     // Teknik Audio Video
            'TEI',     // Teknik Elektronika Industri
            'TKR',     // Teknik Kendaraan Ringan
            'TBSM',    // Teknik dan Bisnis Sepeda Motor
            'TP',      // Teknik Pemesinan
            'TPTU',    // Teknik Pengelasan dan Teknik Umum
            'DKV',     // Desain Komunikasi Visual
            'BKP',     // Bisnis Konstruksi dan Properti
        ];

        foreach ($majors as $majorName) {
            Major::create([
                'major' => $majorName
            ]);
        }
    }
}
