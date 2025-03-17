<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

use App\Models\accounts;
use App\Models\Payment;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class PayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers=Suppliers::all();
        $accounts=accounts::all();
        $Branch=Branch::all();
        $proc=Payment::all();
        return view('pay',compact('suppliers','accounts','Branch','proc'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function storepay(Request $request)
    {
        // التحقق من صحة البيانات
        $validatedData = $request->validate([
            'supplier' => 'required|exists:suppliers,id',
            'account' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'details' => 'required|string',
        ]);
    
        // 1. إضافة السند
        Payment::create([
            'supplier' => $validatedData['supplier'],
            'account' => $validatedData['account'],
            'amount' => $validatedData['amount'],
            'date' => $validatedData['date'],
            'details' => $validatedData['details'],
        ]);
    
        // 2. تحديث المورد
        $supplier = Suppliers::findOrFail($validatedData['supplier']);
        $supplier->debt += $validatedData['amount'];
        $supplier->balance += $validatedData['amount'];
        $supplier->save();
    
        // 3. تحديث الحساب
        $account = accounts::findOrFail($validatedData['account']);
        $account->credit += $validatedData['amount'];
        $account->balance -= $validatedData['amount'];
        $account->save();
    
        // الرد بالنجاح
        return redirect()->back()->with('success', 'تمت إضافة السند بنجاح!');
    }
    

    

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
