<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use App\Models\accounts;
use App\Models\Branch;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers=Suppliers::all();
        $accounts=accounts::all();
        $Branch=Branch::all();
        return view('supply',compact('suppliers','accounts','Branch'));
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
        $data['supplier']=$request->supplier;
        $data['debt']=$request->debt;
        $data['credit']=$request->credit;
        $data['balance']=$request->debt-$request->credit;
        Suppliers::create($data);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Suppliers $suppliers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Suppliers $suppliers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Suppliers $suppliers)
    {
        $s1['supplier']=$request->newName;
        Suppliers::where('id',$request->id)->update($s1);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Suppliers::where('id',$id)->delete();
        return redirect()->back();
    }

    public function det(Request $request)
    {
        $supplier = Suppliers::where('id', $request->supplier)->first();
        $sp['debt'] = $supplier->debt + $request->price;
        $sp['balance'] = $supplier->balance + $request->price;
        Suppliers::where('id',$request->supplier)->update($sp);
        if ($request->paymentType == 'cash') {
            $account = accounts::where('id', $request->account)->first();
            $ac['credit'] = $account->credit + $request->price;
            $ac['balance'] = $account->balance - $request->price;
            accounts::where('id', $request->account)->update($ac);
            $supplier = Suppliers::where('id', $request->supplier)->first();
            $sp['credit'] = $supplier->credit + $request->price;
            $sp['balance'] = $supplier->balance - $request->price;
            Suppliers::where('id',$request->supplier)->update($sp);
        }
        return redirect()->back();

    }
    public function det2(Request $request)
    {
        $supplier = Suppliers::where('id', $request->supplier)->first();
        $sp['credit'] = $supplier->credit + $request->price;
        $sp['balance'] = $supplier->balance - $request->price;
        Suppliers::where('id',$request->supplier)->update($sp);
        $account = accounts::where('id',$request->account)->first();
        $ac['credit'] = $account->credit + $request->price;
        $ac['balance'] = $account->balance - $request->price;
        accounts::where('id',$request->account)->update($ac);
        return redirect()->back();

    }
}
