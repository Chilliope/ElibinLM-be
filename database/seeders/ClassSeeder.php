<?php

namespace Database\Seeders;

use App\Models\ClassTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClassTable::create([
            'class' => 'XII',
            'major_id' => 1,
            'alphabet' => 'B',
            'class_fix' => 'XII PPLG B'
        ]);
    }
}
