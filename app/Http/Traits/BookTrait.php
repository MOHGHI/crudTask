<?php


namespace App\Http\Traits;


use App\Models\Author;
use App\Models\Book;

Trait BookTrait
{
    function addAuthors(Book $book, $authors)
    {
        $authors = collect($authors)->keyBy(function ($item) {
            return $item;
        });

        if(!$book->authors()->exists()) {
            /** @var Collection $booksAuthor */
            $booksAuthor = $book->authors->keyBy('id');
            if($booksAuthor) {
                $syncIds = $booksAuthor->intersectByKeys($authors)->pluck('id')->toArray();
                if($authors) {
                    $authorsToAttach = $authors->diffKeys($booksAuthor);
                    foreach ($authorsToAttach as $key => $value)
                    {
                        $author = Author::find($key);
                        $syncIds[] = $author->id;
                    }
                }
            }
        } else {
            $syncIds = [];
            foreach ($authors as $key => $value) {
                $author = Author::find($value);
                $syncIds[] = $author->id;
            }
        }
        $book->authors()->sync($syncIds);
    }
}