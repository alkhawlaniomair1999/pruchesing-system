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
        $details['tax'] = $request->tax;
        if ($request->tax == 1) {
            $details['price'] = $request->totalPrice * (1-0.15);
        } else {
            $details['price'] = $request->totalPrice;
        }
        details::create($details);
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
        $details['detail'] = $request->detail;
        $details['total'] = $request->totalPrice;
        $details['item_id'] = $request->item;
        $details['branch_id'] = $request->branch;
        $details['account_id'] = $request->account;
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
        details::where('id',$id)->delete();
        return redirect()->back();
    }
        public function getDetails($id){
        //
    }
}
