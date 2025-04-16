<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class invoices extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name','tax_id','address','phone_number','invoice_date','supply_date', 'updated_at', 'created_at'];

}
