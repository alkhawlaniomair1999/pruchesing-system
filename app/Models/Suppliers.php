<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Suppliers extends Model
{
    use HasFactory;

    protected $fillable = ['supplier','debt','credit' ,'balance','updated_at', 'created_at'];

    public function supplyDetails()
    {
        return $this->hasMany(SupplyDetail::class, 'supplier_id');
    }
    

}
