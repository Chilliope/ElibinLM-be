<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;
    protected $table = 'library';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}
