<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\SubBook;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Laravel Up & Running',
                'writer' => 'Mr.Lorem',
                'publisher' => 'O\'Reilly Media',
                'publication_year' => '2019',
                'page_size' => 400,
                'information' => 'Comprehensive guide to Laravel.',
                'image' => 'laravel_up_and_running.jpg',
                'rack_id' => 1,
            ],
            [
                'title' => 'Mastering Laravel',
                'writer' => 'Jane Doe',
                'publisher' => 'Tech Books',
                'publication_year' => '2020',
                'page_size' => 350,
                'information' => 'Advanced techniques in Laravel.',
                'image' => 'mastering_laravel.jpg',
                'rack_id' => 2,
            ],
            // (Add the other books here as well)
        ];

        foreach ($books as $book) {
            // Insert book and get the ID
            $newBook = Book::create([
                'title' => $book['title'],
                'slug' => Str::slug($book['title']),
                'writer' => $book['writer'],
                'publisher' => $book['publisher'],
                'publication_year' => $book['publication_year'],
                'page_size' => $book['page_size'],
                'information' => $book['information'],
                'image' => $book['image'],
                'rack_id' => $book['rack_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
