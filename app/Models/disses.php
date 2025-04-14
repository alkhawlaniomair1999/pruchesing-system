<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class disses extends Model
{
    use HasFactory;

    protected $fillable = ['emp_id','slf','absence','discount','bank', 'updated_at', 'created_at'];
}
