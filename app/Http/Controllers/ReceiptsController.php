<?php

namespace App\Http\Controllers;

use App\Models\receipts;
use Illuminate\Http\Request;

class ReceiptsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receipts = receipts::all();
        return view('receipt', compact('receipts'));
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
        $receipt['customer_name'] = $request->customer_name;
        $receipt['amount'] = $request->amount;
        $receipt['payment_method'] = $request->payment_method;
        $receipt['date'] = $request->date;
        $receipt['detail'] = $request->detail;
        receipts::create($receipt);
        return redirect()->back()->with('success', 'Receipt created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(receipts $receipts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(receipts $receipts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, receipts $receipts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(receipts $receipts)
    {
        //
    }
    public function printreceipt($id)
    {
        $receipt = receipts::find($id);
        return view('print_receipt', compact('receipt'));
    }
}
