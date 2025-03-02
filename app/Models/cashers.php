<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class cashers extends Model
{
    use HasFactory;

    protected $fillable = ['casher','branch_id', 'updated_at', 'created_at'];
}
