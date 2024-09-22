<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryMember extends Model
{
    use HasFactory;
    protected $table = 'library_members';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
    
    function major() : BelongsTo {
        return $this->belongsTo(Major::class, 'major_id');
    }
}
