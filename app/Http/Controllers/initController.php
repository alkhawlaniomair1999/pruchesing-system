<?php

namespace App\Http\Controllers;

use App\Models\cashers;
use Illuminate\Http\Request;
use App\Models\accounts;
use App\Models\items;
use App\Models\Branch;


class initController extends Controller
{
    public function index()
    {
        $branchs=Branch::All();
        $data = items::All();
        $data1=accounts::All();
        $casher=cashers::All();
        return view('init',['data'=>$data ,'data1'=>$data1,'branchs'=>$branchs,'casher'=>$casher]);
    }
}
