<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'biography',
    ];

    public function images(){
        return $this->morphMany(Image::class, 'transactionable', 'transactionable_type', 'transactionable_id');
    }

    public function books(){
        return $this->belongsToMany(Book::class);
    }
}
