<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class accounts extends Model
{
    use HasFactory;

    protected $fillable = ['account', 'updated_at', 'created_at'];
}
