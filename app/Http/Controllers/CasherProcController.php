<?php

namespace App\Http\Controllers;

use App\Models\casher_procs;
use App\Models\cashers;
use App\Models\Branch;
use App\Models\accounts;



use Illuminate\Http\Request;

class CasherProcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $casher_proc=casher_procs::all();
        $casher=cashers::all();
        $branch=Branch::all();
        
        return view('casherproc',['casher_proc'=>$casher_proc,'casher'=>$casher,'branch'=>$branch]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data['casher_id'] = $request->casher;
        $data['date'] = $request->date;
        $data['total'] = $request->total;
        $data['bank'] = $request->bank;
        $data['cash'] = $request->cash;
        $data['out'] = $request->out;
        $data['plus'] = $request->total - ($request->out+$request->cash+$request->bank);
        casher_procs::create($data);

        return redirect()->back();
    }

    
    public function show( $casher_proc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $casher_proc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $casher_proc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $casher_proc)
    {
        //
    }
}
