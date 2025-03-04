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
        $cacher = cashers::where('id',$request->casher)->first();
        $box = accounts::where('branch_id',$cacher->branch_id)->where('type','box')->first();
        $box1 = $box->debt + $request->cash;
        $balance = $box->balance + $request->cash;
        accounts::where('id',$box->id)->update(['debt'=>$box1,'balance'=>$balance]);
        $bank = accounts::where('branch_id',$cacher->branch_id)->where('type','bank')->first();
        $bank1 = $bank->debt + $request->bank;
        $balance = $bank->balance + $request->bank;
        accounts::where('id',$bank->id)->update(['debt'=>$bank1,'balance'=>$balance]);
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
    public function update(Request $request)
    {
        $cp = casher_procs::where('id',$request->id)->first();
        $c = cashers::where('id',$cp->casher_id)->first();
        $data['casher_id'] = $request->casher_id;
        $data['date'] = $request->date;
        $data['total'] = $request->total;
        $data['bank'] = $request->bank;
        $data['cash'] = $request->cash;
        $data['out'] = $request->out;
        $data['plus'] = $request->total - ($request->out+$request->cash+$request->bank);
        casher_procs::where('id',$request->id)->update($data);
        if ($cp->casher_id == $request->casher_id) {
            $cacher = cashers::where('id',$request->casher_id)->first();
            $box = accounts::where('branch_id',$cacher->branch_id)->where('type','box')->first();
            $box1 = $box->debt - $cp->cash + $request->cash;
            $balance = $box->balance - $cp->cash + $request->cash;
            accounts::where('id',$box->id)->update(['debt'=>$box1,'balance'=>$balance]);
            $bank = accounts::where('branch_id',$cacher->branch_id)->where('type','bank')->first();
            $bank1 = $bank->debt - $cp->bank + $request->bank;
            $balance = $bank->balance - $cp->bank + $request->bank;
            accounts::where('id',$bank->id)->update(['debt'=>$bank1,'balance'=>$balance]);
        } else {
            $c2 = cashers::where('id',$request->casher_id)->first();
            if ($c->branch_id == $c2->branch_id) {
                $cacher = cashers::where('id',$request->casher_id)->first();
                $box = accounts::where('branch_id',$cacher->branch_id)->where('type','box')->first();
                $box1 = $box->debt - $cp->cash + $request->cash;
                $balance = $box->balance - $cp->cash + $request->cash;
                accounts::where('id',$box->id)->update(['debt'=>$box1,'balance'=>$balance]);
                $bank = accounts::where('branch_id',$cacher->branch_id)->where('type','bank')->first();
                $bank1 = $bank->debt - $cp->bank + $request->bank;
                $balance = $bank->balance - $cp->bank + $request->bank;
                accounts::where('id',$bank->id)->update(['debt'=>$bank1,'balance'=>$balance]);
            } else {
                $box = accounts::where('branch_id',$c->branch_id)->where('type','box')->first();
                $box1 = $box->debt - $cp->cash;
                $balance = $box->balance - $request->cash;
                accounts::where('id',$box->id)->update(['debt'=>$box1,'balance'=>$balance]);
                $bank = accounts::where('branch_id',$c->branch_id)->where('type','bank')->first();
                $bank1 = $bank->debt - $cp->bank;
                $balance = $bank->balance - $cp->bank;
                accounts::where('id',$bank->id)->update(['debt'=>$bank1,'balance'=>$balance]);
                $cacher = cashers::where('id',$request->casher_id)->first();
                $box = accounts::where('branch_id',$cacher->branch_id)->where('type','box')->first();
                $box1 = $box->debt + $request->cash;
                $balance = $box->balance + $request->cash;
                accounts::where('id',$box->id)->update(['debt'=>$box1,'balance'=>$balance]);
                $bank = accounts::where('branch_id',$cacher->branch_id)->where('type','bank')->first();
                $bank1 = $bank->debt + $request->bank;
                $balance = $bank->balance + $request->bank;
                accounts::where('id',$bank->id)->update(['debt'=>$bank1,'balance'=>$balance]);
            }
            
        }
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $cp = casher_procs::where('id',$id)->first();
        $c = cashers::where('id',$cp->casher_id)->first();
        $b = Branch::where('id',$c->branch_id)->first();
        $box = accounts::where('branch_id',$b->id)->where('type','box')->first();
        $box1 = $box->debt - $cp->cash;
        $balance = $box->balance - $cp->cash;
        accounts::where('id',$box->id)->update(['debt'=>$box1,'balance'=>$balance]);
        $bank = accounts::where('branch_id',$b->id)->where('type','bank')->first();
        $bank1 = $bank->debt - $cp->bank;
        $balance = $bank->balance - $cp->bank;
        accounts::where('id',$bank->id)->update(['debt'=>$bank1,'balance'=>$balance]);
        casher_procs::where('id',$id)->delete();
        return redirect()->back();
    }
}
