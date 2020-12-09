<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'name', 'last_name','photo',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'author_book');

    }

    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
}
