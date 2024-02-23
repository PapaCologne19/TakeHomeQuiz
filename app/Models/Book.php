<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $table = "books";
    protected $fillable = [
        'author_id',
        'book_title',
        'date_published',
    ];

    public function images(){
        return $this->morphMany(Image::class, 'transactionable', 'transactionable_type', 'transactionable_id');
    }

    public function authors(){
        return $this->belongsToMany(Author::class);
    }
}
