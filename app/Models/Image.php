<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

Relation::morphMap([
    'Book' => 'App/Models/Book',
    'Author' => 'App/Models/Author',
]);

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename'
    ];

    public function transactionable(){
        return $this->morphTo();
    }
}
