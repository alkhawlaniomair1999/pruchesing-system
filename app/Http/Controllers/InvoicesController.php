<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoice_details;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = invoices::all();
        return view('invoice', compact('invoices'));
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
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'tax_id' => 'required|integer',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|integer',
            'invoice_date' => 'required|date',
            'supply_date' => 'nullable|date',
        ]);

        invoices::create($request->all());
        $id = invoices::latest()->first();
        $invoice = invoices::findOrFail($id->id);
        $invoice_details = invoice_details::where('invoice_id', $id->id)->get();
        return view('invoice_details', compact('invoice','invoice_details'))->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function details(Request $request)
    {
        $detail['product_name'] = $request->product_name;
        $detail['quantity'] = $request->quantity;
        $detail['price'] = $request->price;
        $detail['product_code'] = $request->product_code;
        $detail['invoice_id'] = $request->invoice_id;
        $detail['tax'] = $request->tax;
        if ($request->discount == null) {
            $detail['discount'] = 0;
        } else {
            $detail['discount'] = $request->discount;
        }
        invoice_details::create($detail);
        $invoice = invoices::findOrFail($request->invoice_id);
        $invoice_details = invoice_details::where('invoice_id', $request->invoice_id)->get();
        return view('invoice_details', compact('invoice', 'invoice_details'))->with('success', 'Invoice created successfully.');
    }
    public function update_detail(Request $request)
    {
        $detail = invoice_details::findOrFail($request->id);
        $detail['product_name'] = $request->product_name;
        $detail['quantity'] = $request->quantity;
        $detail['price'] = $request->price;
        $detail['product_code'] = $request->product_code;
        $detail['tax'] = $request->tax;
        if ($request->discount == null) {
            $detail['discount'] = 0;
        } else {
            $detail['discount'] = $request->discount;
        }
        $detail->save();
        $invoice = invoices::findOrFail($request->invoice_id);
        $invoice_details = invoice_details::where('invoice_id', $request->invoice_id)->get();
        return view('invoice_details', compact('invoice', 'invoice_details'))->with('success', 'Invoice updated successfully.');    
    }
    public function destroy_detail($id)
    {
        $detail = invoice_details::findOrFail($id);
        $invoice_id = $detail->invoice_id;
        $detail->delete();
        $invoice = invoices::findOrFail($invoice_id);
        $invoice_details = invoice_details::where('invoice_id', $invoice_id)->get();
        return view('invoice_details', compact('invoice', 'invoice_details'))->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices $invoices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices $invoices)
    {
        $invoice = invoices::findOrFail($request->id);
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'tax_id' => 'required|integer',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|integer',
            'invoice_date' => 'required|date',
            'supply_date' => 'nullable|date',
        ]);
        $invoice->update($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $invoice = invoices::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
