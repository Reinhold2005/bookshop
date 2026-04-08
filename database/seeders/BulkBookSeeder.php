<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BulkBookSeeder extends Seeder
{
    public function run()
    {
        $books = [
            // Existing books...
            [
                'title' => 'The Alchemist',
                'author' => 'Paulo Coelho',
                'description' => 'A magical story about following your dreams',
                'price' => 14.99,
                'stock' => 25,
                'category' => 'Fiction',
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'description' => 'Build good habits and break bad ones',
                'price' => 16.99,
                'stock' => 50,
                'category' => 'Self-Help',
            ],
            // Add more books here...
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}