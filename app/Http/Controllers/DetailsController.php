<?php

namespace App\Http\Controllers;

use App\Models\details;
use App\Models\accounts;
use App\Models\Branch;
use App\Models\items;
use Illuminate\Http\Request;

class DetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = items::all();
        $Branch = Branch::all();
        $accounts = accounts::all();
        $details = details::all();
        return view('details',['items'=>$items,'Branch'=>$Branch,'accounts'=>$accounts,'details'=>$details]);
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
        $details['detail'] = $request->detail;
        $details['total'] = $request->totalPrice;
        $details['item_id'] = $request->item;
        $details['branch_id'] = $request->branch;
        $details['account_id'] = $request->account;
        $details['date'] = $request->date;
        $details['tax'] = $request->tax;
        if ($request->tax == 1) {
            $details['price'] = $request->totalPrice * (1-0.15);
        } else {
            $details['price'] = $request->totalPrice;
        }
        details::create($details);
$acc=accounts::where('id',$request->account)->first();
$credit=$acc['credit']+$request->totalPrice;
$balance=$acc['balance']-$request->totalPrice;
accounts::where('id',$request->account)->update(['credit'=>$credit,'balance'=>$balance]);


        return redirect()->back();
        
    }

    /**
     * Display the specified resource.
     */
    public function show(details $details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(details $details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $det = details::where('id',$request->id)->first();
        $ce= accounts::where('id',$det['account_id'])->first();
        if ($det['account_id'] == $request->account) {
            $c2 = $ce['credit'] - $det['total'] + $request->totalPrice;
            accounts::where('id',$ce['id'])->update(['credit'=>$c2]);
            $acc=accounts::where('id',$ce['id'])->first();
            $balance=$acc['debt']-$acc['credit'];
            accounts::where('id',$ce['id'])->update(['balance'=>$balance]);
        } else {
            $c2 = $ce['credit'] - $det['total'];
            accounts::where('id',$ce['id'])->update(['credit'=>$c2]);
            $ac=accounts::where('id',$ce['id'])->first();
            $balance=$ac['debt']-$ac['credit'];
            accounts::where('id',$ce['id'])->update(['balance'=>$balance]);
            $acc=accounts::where('id',$request->account)->first();
            $credit=$acc['credit']+$request->totalPrice;
            accounts::where('id',$request->account)->update(['credit'=>$credit]);
            $acc1=accounts::where('id',$request->account)->first();
            $balance=$acc1['debt']-$acc1['credit'];
            accounts::where('id',$request->account)->update(['balance'=>$balance]);
            # code...
        }
        $details['detail'] = $request->detail;
        $details['total'] = $request->totalPrice;
        $details['item_id'] = $request->item;
        $details['branch_id'] = $request->branch;
        $details['account_id'] = $request->account;
        $details['date'] = $request->date;
        $details['tax'] = $request->tax;
        if ($request->tax == 1) {
            $details['price'] = $request->totalPrice * (1-0.15);
        } else {
            $details['price'] = $request->totalPrice;
        }
        details::where('id',$request->id)->update($details);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       $c1=details::where('id',$id)->first();
       $ce= accounts::where('id',$c1['account_id'])->first();
            $c2 = $ce['credit'] - $c1['total'];
            $balance=$ce['balance']+$c1['total'];
    accounts::where('id',$ce['id'])->update(['credit'=>$c2]);
    $ce= accounts::where('id',$c1['account_id'])->first();
    $balance=$ce['debt']-$ce['credit'];
    accounts::where('id',$ce['id'])->update(['balance'=>$balance]);  
    details::where('id',$id)->delete();
  
       return redirect()->back();
    }
        public function getDetails($id){
        //
    }
}
