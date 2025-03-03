<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class accounts extends Model
{
    use HasFactory;

    protected $fillable = ['account','debt','credit' ,'balance','branch_id','updated_at', 'created_at'];
}
