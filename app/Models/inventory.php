<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class inventory extends Model
{
    use HasFactory;

    protected $fillable = ['item_id','branch_id','month','year','first_inventory','last_inventory','inventory_out', 'updated_at', 'created_at'];

}
