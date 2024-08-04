<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    function rack() : BelongsTo {
        return $this->belongsTo(Rack::class, 'rack_id');
    }
}
