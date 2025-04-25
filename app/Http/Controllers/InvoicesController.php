<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoice_details;
use Illuminate\Http\Request;
use Exception;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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

            return view('invoice_details', compact('invoice','invoice_details'))
                ->with('success', 'تم إضافة الفاتورة بنجاح!');
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error', 'فشل إضافة الفاتورة! ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function details(Request $request)
    {
        try {
            $detail['product_name'] = $request->product_name;
            $detail['quantity'] = $request->quantity;
            $detail['price'] = $request->price;
            $detail['product_code'] = $request->product_code;
            $detail['invoice_id'] = $request->invoice_id;
            $detail['tax'] = $request->tax;
            $detail['discount'] = $request->discount ?? 0;

            invoice_details::create($detail);

            $invoice = invoices::findOrFail($request->invoice_id);
            $invoice_details = invoice_details::where('invoice_id', $request->invoice_id)->get();

            return view('invoice_details', compact('invoice', 'invoice_details'))
                ->with('success', 'تم إضافة الصنف بنجاح!');
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error', 'فشل إضافة الصنف! ' . $ex->getMessage());
        }
    }
    
    public function update_detail(Request $request)
    {
        try {
            $detail = invoice_details::findOrFail($request->id);
            $detail['product_name'] = $request->product_name;
            $detail['quantity'] = $request->quantity;
            $detail['price'] = $request->price;
            $detail['product_code'] = $request->product_code;
            $detail['tax'] = $request->tax;
            $detail['discount'] = $request->discount ?? 0;
            $detail->save();

            $invoice = invoices::findOrFail($request->invoice_id);
            $invoice_details = invoice_details::where('invoice_id', $request->invoice_id)->get();

            return view('invoice_details', compact('invoice', 'invoice_details'))
                ->with('success', 'تم تعديل الصنف بنجاح!');
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error', 'فشل تعديل الصنف! ' . $ex->getMessage());
        }
    }

    public function destroy_detail($id)
    {
        try {
            $detail = invoice_details::findOrFail($id);
            $invoice_id = $detail->invoice_id;
            $detail->delete();

            $invoice = invoices::findOrFail($invoice_id);
            $invoice_details = invoice_details::where('invoice_id', $invoice_id)->get();

            return view('invoice_details', compact('invoice', 'invoice_details'))
                ->with('success', 'تم حذف الصنف بنجاح!');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'فشل حذف الصنف! ' . $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices $invoices)
    {
        try {
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

            return redirect()->route('invoices.index')->with('success', 'تم تعديل الفاتورة بنجاح!');
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error', 'فشل تعديل الفاتورة! ' . $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $invoice = invoices::findOrFail($id);
            $invoice->delete();

            return redirect()->route('invoices.index')->with('success', 'تم حذف الفاتورة بنجاح!');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'فشل حذف الفاتورة! ' . $ex->getMessage());
        }
    }

    public function print($id)
    {
        try {
            $invoice = invoices::findOrFail($id);
            $invoice_details = invoice_details::where('invoice_id', $id)->get();
            return view('print/print_invoice', compact('invoice', 'invoice_details'));
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'فشل طباعة الفاتورة! ' . $ex->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $invoice = invoices::findOrFail($id);
            $invoice_details = invoice_details::where('invoice_id', $id)->get();
            return view('invoice_details', compact('invoice', 'invoice_details'));
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'تعذر عرض تفاصيل الفاتورة! ' . $ex->getMessage());
        }
    }
}