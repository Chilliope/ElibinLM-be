<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gate extends Model
{
    use HasFactory;

    protected $table = 'gates';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    function book() :  BelongsTo {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
