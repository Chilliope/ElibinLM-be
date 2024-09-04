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
        $classes = [
            ['class' => 'XII', 'major_id' => 1, 'alphabet' => 'A', 'class_fix' => 'XII PPLG A'],
            ['class' => 'XII', 'major_id' => 1, 'alphabet' => 'B', 'class_fix' => 'XII PPLG B'],
            ['class' => 'XII', 'major_id' => 2, 'alphabet' => 'A', 'class_fix' => 'XII TKJ A'],
            ['class' => 'XII', 'major_id' => 2, 'alphabet' => 'B', 'class_fix' => 'XII TKJ B'],
            ['class' => 'XII', 'major_id' => 3, 'alphabet' => 'A', 'class_fix' => 'XII RPL A'],
            ['class' => 'XII', 'major_id' => 3, 'alphabet' => 'B', 'class_fix' => 'XII RPL B'],
            ['class' => 'XI', 'major_id' => 1, 'alphabet' => 'A', 'class_fix' => 'XI PPLG A'],
            ['class' => 'XI', 'major_id' => 1, 'alphabet' => 'B', 'class_fix' => 'XI PPLG B'],
            ['class' => 'XI', 'major_id' => 2, 'alphabet' => 'A', 'class_fix' => 'XI TKJ A'],
            ['class' => 'XI', 'major_id' => 2, 'alphabet' => 'B', 'class_fix' => 'XI TKJ B'],
            ['class' => 'XI', 'major_id' => 3, 'alphabet' => 'A', 'class_fix' => 'XI RPL A'],
            ['class' => 'XI', 'major_id' => 3, 'alphabet' => 'B', 'class_fix' => 'XI RPL B'],
        ];

        foreach ($classes as $classData) {
            ClassTable::create($classData);
        }
    }
}
