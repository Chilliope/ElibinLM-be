<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visitor extends Model
{
    use HasFactory;
    
    protected $table = 'visitors';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    function class() : BelongsTo {
        return $this->belongsTo(ClassTable::class, 'class_id');
    }
}
