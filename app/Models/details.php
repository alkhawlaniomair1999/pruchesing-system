<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class details extends Model
{
    use HasFactory;

    protected $fillable = ['detail','total','tax','price','item_id','branch_id','account_id', 'updated_at', 'created_at'];
}
