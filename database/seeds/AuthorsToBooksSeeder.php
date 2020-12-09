<?php

use Illuminate\Database\Seeder;

class AuthorsToBooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = \App\Models\Book::all();
        \App\Models\Author::all()->each(function ($author) use ($books){
            $author->books()->attach(
                $books->random(rand(1,3))->pluck('id')->toArray()
            );
        });
    }
}
