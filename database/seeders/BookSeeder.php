<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('books')->insert([
        [
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'description' => 'A story of wealth and love in the Jazz Age',
            'price' => 12.99,
            'stock' => 10,
            'category' => 'Fiction',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'title' => '1984',
            'author' => 'George Orwell',
            'description' => 'A dystopian social science fiction novel',
            'price' => 14.99,
            'stock' => 15,
            'category' => 'Science Fiction',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        // Coming to add more books...
    ]);
}
}
