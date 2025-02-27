<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accounts;
use App\Models\items;
use App\Models\Branch;

class initController extends Controller
{
    public function index()
    {
        $data = items::All();
        return view('init',['data'=>$data]);
    }
}
