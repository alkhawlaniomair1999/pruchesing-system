<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class items extends Model
{
    use HasFactory;

    protected $fillable = ['item', 'updated_at', 'created_at'];
}
