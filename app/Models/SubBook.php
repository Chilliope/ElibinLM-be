<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubBook extends Model
{
    use HasFactory;

    protected $table = 'sub_books';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function book() : BelongsTo {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
