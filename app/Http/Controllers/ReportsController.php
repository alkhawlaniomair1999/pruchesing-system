<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accounts;
use App\Models\Branch;
use App\Models\details;
use App\Models\items;
use App\Models\cashers;
use App\Models\casher_procs;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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


public function branch(Request $request)
{
    $branchId = $request->input('branch');
    $month = $request->input('month');
    $year = $request->input('year');

    // حساب عدد أيام الشهر
    $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;


    $operations = casher_procs::select(
        DB::raw('DATE(date) as operation_date'),
        DB::raw('SUM(total) as total_sum'),
        DB::raw('SUM(cash) as cash_sum'),
        DB::raw('SUM(`out`) as out_sum'), // إضافة backticks حول out
        DB::raw('SUM(bank) as bank_sum'),

        DB::raw('SUM(plus) as plus_sum')
    )
    ->whereHas('casher', function ($query) use ($branchId) {
        $query->whereHas('branch', function ($subQuery) use ($branchId) {
            $subQuery->where('branch_id', $branchId);
        });
    })
    ->whereYear('date', $year)
    ->whereMonth('date', $month)
    ->groupBy('operation_date')
    ->get();
$branch=Branch::All();
    return view('branch_report', compact('operations', 'month', 'year', 'daysInMonth','branch','branchId'));
}

public function total(Request $request)
{
    $month = $request->input('month');
    $year = $request->input('year');

    // حساب عدد أيام الشهر
    $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;


    $operations = casher_procs::select(
        DB::raw('DATE(date) as operation_date'),
        DB::raw('SUM(total) as total_sum'),
        DB::raw('SUM(cash) as cash_sum'),
        DB::raw('SUM(`out`) as out_sum'), // إضافة backticks حول out
        DB::raw('SUM(bank) as bank_sum'),
        DB::raw('SUM(plus) as plus_sum')
    )
    ->whereYear('date', $year)
    ->whereMonth('date', $month)
    ->groupBy('operation_date')
    ->get();
    return view('total_report', compact('operations', 'month', 'year', 'daysInMonth'));
}
}

    













