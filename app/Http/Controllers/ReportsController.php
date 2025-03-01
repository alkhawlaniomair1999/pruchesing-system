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
        return view('month_report');
       
    }

    public function monthly(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $details = details::whereYear('date', $year)->whereMonth('date', $month)->get();
        $accounts = accounts::all();
        $branches = Branch::all();
        $items = items::all();

        return view('reports',['accounts'=>$accounts,'branches'=>$branches,'details'=>$details,'items'=>$items,'month'=>$month,'year'=>$year]);
    }













}

