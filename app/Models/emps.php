<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class emps extends Model
{
    use HasFactory;

    protected $fillable = ['name_emp','branch','country','salary','date_hirring', 'updated_at', 'created_at'];

}
