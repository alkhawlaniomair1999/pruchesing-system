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

        $accounts = accounts::whereYear('updated_at', $year)->whereMonth('updated_at', $month)->get();
        $branches = Branch::whereYear('updated_at', $year)->whereMonth('updated_at', $month)->get();
        $details = details::whereYear('updated_at', $year)->whereMonth('updated_at', $month)->get();
        $items = items::whereYear('updated_at', $year)->whereMonth('updated_at', $month)->get();

        return view('reports',['accounts'=>$accounts,'branches'=>$branches,'details'=>$details,'items'=>$items,'month'=>$month,'year'=>$year]);
    }













}

