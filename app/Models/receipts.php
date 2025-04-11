<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class receipts extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name','amount','payment_method','date','detail','updated_at', 'created_at'];
}
