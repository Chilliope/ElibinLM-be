<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassTable extends Model
{
    use HasFactory;

    protected $table = 'class';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    function major() : BelongsTo {
        return $this->belongsTo(Major::class, 'major_id');
    }
}
