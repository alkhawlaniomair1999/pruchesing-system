<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Cash;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class casher_procs extends Model
{
    use HasFactory;

    
    protected $fillable = ['total','date','bank','cash','out','plus','casher_id', 'updated_at', 'created_at'];
    public function casher()
{
    return $this->belongsTo(Cashers::class);
}


}
