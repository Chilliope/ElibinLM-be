<?php

namespace Database\Seeders;

use App\Models\Visitor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $roles = ['umum', 'guru', 'siswa', 'tenaga kependidikan'];

        for ($i = 1; $i <= 14; $i++) {
            Visitor::create([
                'name' => $faker->name,
                'class_id' => $faker->numberBetween(1, 12), // assuming there are 12 classes
                'role' => $roles[array_rand($roles)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
