<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrower extends Model
{
    use HasFactory;
    
    protected $table = 'borrowers';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    function class() : BelongsTo {
        return $this->belongsTo(ClassTable::class, 'class_id');
    }

    function book() : BelongsTo {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
