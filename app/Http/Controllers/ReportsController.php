<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accounts;
use App\Models\Branch;
use App\Models\details;
use App\Models\items;

class ReportsController extends Controller
{
    public function index()
    {
        $accounts = accounts::all();
        $branches = Branch::all();
        $details = details::all();
        $items = items::all();
       
        return view('reports',['accounts'=>$accounts,'branches'=>$branches,'details'=>$details,'items'=>$items]);
    }















}

