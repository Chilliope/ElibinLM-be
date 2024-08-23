<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'Laravel Up & Running',
            'slug' => Str::slug('Laravel Up & Running'),
            'writer' => 'Mr.Lorem',
            'publisher' => 'O\'Reilly Media',
            'ISBN' => 9781492041212,
            'publication_year' => '2019',
            'book_spine_number' => '001',
            'page_size' => 400,
            'information' => 'Comprehensive guide to Laravel.',
            'image' => 'laravel_up_and_running.jpg',
            'stock' => 10,
            'rack_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Book::create([
            'title' => 'Laravel Up & Running 2',
            'slug' => Str::slug('Laravel Up & Running 2'),
            'writer' => 'Mr.Lorem',
            'publisher' => 'O\'Reilly Media',
            'ISBN' => 9781492041212,
            'publication_year' => '2019',
            'book_spine_number' => '001',
            'page_size' => 400,
            'information' => 'Comprehensive guide to Laravel.',
            'image' => 'laravel_up_and_running.jpg',
            'stock' => 10,
            'rack_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        Book::create([
            'title' => 'Laravel Up & Running 3',
            'slug' => Str::slug('Laravel Up & Running 3'),
            'writer' => 'Mr.Lorem',
            'publisher' => 'O\'Reilly Media',
            'ISBN' => 9781492041212,
            'publication_year' => '2019',
            'book_spine_number' => '001',
            'page_size' => 400,
            'information' => 'Comprehensive guide to Laravel.',
            'image' => 'laravel_up_and_running.jpg',
            'stock' => 10,
            'rack_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
