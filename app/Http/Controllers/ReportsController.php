<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accounts;
use App\Models\Branch;
use App\Models\details;
use App\Models\items;
use App\Models\cashers;
use App\Models\casher_procs;

class ReportsController extends Controller
{
    public function index()
    {
        $cashers = cashers::all();
        $branches = Branch::all();
        
        return view('month_report',['cashers'=>$cashers,'branches'=>$branches]);
       
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
    public function casher(Request $request)
    {
        $casher = $request->input('casher');
        $month = $request->input('month');
        $year = $request->input('year');

        $c = cashers::where('id',$casher)->first();
        $casher_proc = casher_procs::whereYear('date', $year)->whereMonth('date', $month)->where('casher_id',$casher)->get();

        return view('casher_report',['month'=>$month,'year'=>$year,'c'=>$c,'casher_proc'=>$casher_proc]);
    }













}

