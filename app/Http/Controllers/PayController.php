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
    
    public function updatePay(Request $request){
         // التحقق من صحة البيانات
    $validatedData = $request->validate([
        'supplier' => 'required|exists:suppliers,id',
        'account' => 'required|exists:accounts,id',
        'amount' => 'required|numeric|min:0',
        'date' => 'required|date',
        'details' => 'required|string',
    ]);

    // جلب السند الحالي
    $payment = Payment::findOrFail($request->id);

    // حساب الفرق في المبلغ
    $amountDifference = $validatedData['amount'] - $payment->amount;

    // إذا تغير المورد، قم بإعادة القيم إلى المورد القديم
    if ($payment->supplier != $validatedData['supplier']) {
        $oldSupplier = Suppliers::findOrFail($payment->supplier);
        $oldSupplier->debt -= $payment->amount;
        $oldSupplier->balance -= $payment->amount;
        $oldSupplier->save();

        // تحديث المورد الجديد
        $newSupplier = Suppliers::findOrFail($validatedData['supplier']);
        $newSupplier->debt += $validatedData['amount'];
        $newSupplier->balance += $validatedData['amount'];
        $newSupplier->save();
    } else {
        // إذا لم يتغير المورد، فقط قم بتحديث القيم بناءً على الفرق
        $supplier = Suppliers::findOrFail($validatedData['supplier']);
        $supplier->debt += $amountDifference;
        $supplier->balance += $amountDifference;
        $supplier->save();
    }

    // إذا تغير الحساب، قم بإعادة القيم إلى الحساب القديم
    if ($payment->account != $validatedData['account']) {
        $oldAccount = accounts::findOrFail($payment->account);
        $oldAccount->credit -= $payment->amount;
        $oldAccount->balance += $payment->amount;
        $oldAccount->save();

        // تحديث الحساب الجديد
        $newAccount = accounts::findOrFail($validatedData['account']);
        $newAccount->credit += $validatedData['amount'];
        $newAccount->balance -= $validatedData['amount'];
        $newAccount->save();
    } else {
        // إذا لم يتغير الحساب، فقط قم بتحديث القيم بناءً على الفرق
        $account = accounts::findOrFail($validatedData['account']);
        $account->credit += $amountDifference;
        $account->balance -= $amountDifference;
        $account->save();
    }

    // تحديث السند
    $payment->update([
        'supplier' => $validatedData['supplier'],
        'account' => $validatedData['account'],
        'amount' => $validatedData['amount'],
        'date' => $validatedData['date'],
        'details' => $validatedData['details'],
    ]);

    // الرد بالنجاح
    return redirect()->back()->with('success', 'تم تعديل السند بنجاح!');
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
        
    // جلب السند الحالي
    $payment = Payment::findOrFail($id);

    // إعادة القيم إلى المورد
    $supplier = Suppliers::findOrFail($payment->supplier);
    $supplier->debt -= $payment->amount;
    $supplier->balance -= $payment->amount;
    $supplier->save();

    // إعادة القيم إلى الحساب
    $account = accounts::findOrFail($payment->account);
    $account->credit -= $payment->amount;
    $account->balance += $payment->amount;
    $account->save();

    // حذف السند
    $payment->delete();

    // الرد بالنجاح
    return redirect()->back()->with('success', 'تم حذف السند بنجاح!');
    }
}
