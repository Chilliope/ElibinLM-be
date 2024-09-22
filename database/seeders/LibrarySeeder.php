<?php

namespace Database\Seeders;

use App\Models\Library;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Library::create([
            // 'library_number' => 242442,
            'library' => 'SMKN 2 Banjarmasin ',
            // 'school' => 'SMKN 2 Banjarmasin',
            // 'address' => 'Kayu Tangi',
            // 'subdistrict' => 'Banjarmasin Utara',
            // 'city' => 'Banjarmasin',
            // 'province' => 'Kalimantan Selatan',
            // 'post_code' => 172833,
            // 'phone' => '085389172080',
            // 'website' => 'https://smkn2-bjm.sch.id/',
            // 'email' => 'lib@gmail.com',
            // 'institutional_status' => 'Negeri',
            // 'since' => 2018,
            // 'land_width' => '20',
            // 'building_area' => '20',
            // 'headmaster' => 'Mr.Lorem',
            'head_librarian' => 'Ms.Ipsum',
            // 'vision' => '-',
            // 'mission' => '-',
            // 'short_history' => '-',
            // 'opening_hours' => '-',
            // 'library_activity' => '-'
        ]);
    }
}
