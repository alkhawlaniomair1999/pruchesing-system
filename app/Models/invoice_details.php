<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class invoice_details extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id','product_code','product_name','quantity','price','discount','tax', 'updated_at', 'created_at'];

}
