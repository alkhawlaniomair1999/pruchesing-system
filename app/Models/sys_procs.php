<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sys_procs extends Model
{
    use HasFactory;

    protected $fillable = ['detail','proc_type','acc_type','account_id','debt','credit' ,'balance','date','updated_at', 'created_at'];
}
