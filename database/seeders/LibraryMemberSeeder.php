<?php

namespace Database\Seeders;

use App\Models\LibraryMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LibraryMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 25; $i++) {
            LibraryMember::create([
                'NIS' => $faker->unique()->numberBetween(100000, 999999),
                'name' => $faker->name,
                'place_of_birth' => $faker->city,
                'date_of_birth' => $faker->date('Y-m-d', '2005-12-31'),
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'class_id' => $faker->numberBetween(1, 12), // assuming there are 12 classes
                'image' => 'profile_' . $i . '.jpg', // just a placeholder image name
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
