<?php
namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\payment;
use App\Models\Suppliers;
use App\Models\Branch;
use App\Models\SystemOperation;
use Illuminate\Http\Request;
use Exception;

class PayController extends Controller
{
    public function index()
    {
        try {
            $suppliers = Suppliers::all();
            $accounts = accounts::all();
            $Branch = Branch::all();
            $proc = payment::all();
            return view('pay', compact('suppliers', 'accounts', 'Branch', 'proc'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب البيانات.');
        }
    }

    public function create()
    {
        // تنفيذ الكود هنا
    }

    public function storepay(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'supplier' => 'required|exists:suppliers,id',
                'account' => 'required|exists:accounts,id',
                'amount' => 'required|numeric|min:0',
                'date' => 'required|date',
                'details' => 'required|string',
            ]);

            payment::create([
                'supplier' => $validatedData['supplier'],
                'account' => $validatedData['account'],
                'amount' => $validatedData['amount'],
                'date' => $validatedData['date'],
                'details' => $validatedData['details'],
            ]);

            $supplier = Suppliers::findOrFail($validatedData['supplier']);
            $supplier->debt += $validatedData['amount'];
            $supplier->balance += $validatedData['amount'];
            $supplier->save();

            $account = accounts::findOrFail($validatedData['account']);
            $account->credit += $validatedData['amount'];
            $account->balance -= $validatedData['amount'];
            $account->save();

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'إضافة',
                'details' => 'إضافة سند دفع - المورد: ' . $supplier->supplier . ', الحساب: ' . $account->account . ', المبلغ: ' . $validatedData['amount'],
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تمت إضافة السند بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة السند.');
        }
    }

    public function updatePay(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'supplier' => 'required|exists:suppliers,id',
                'account' => 'required|exists:accounts,id',
                'amount' => 'required|numeric|min:0',
                'date' => 'required|date',
                'details' => 'required|string',
            ]);

            $payment = payment::findOrFail($request->id);
            $amountDifference = $validatedData['amount'] - $payment->amount;

            if ($payment->supplier != $validatedData['supplier']) {
                $oldSupplier = Suppliers::findOrFail($payment->supplier);
                $oldSupplier->debt -= $payment->amount;
                $oldSupplier->balance -= $payment->amount;
                $oldSupplier->save();

                $newSupplier = Suppliers::findOrFail($validatedData['supplier']);
                $newSupplier->debt += $validatedData['amount'];
                $newSupplier->balance += $validatedData['amount'];
                $newSupplier->save();
            } else {
                $supplier = Suppliers::findOrFail($validatedData['supplier']);
                $supplier->debt += $amountDifference;
                $supplier->balance += $amountDifference;
                $supplier->save();
            }

            if ($payment->account != $validatedData['account']) {
                $oldAccount = accounts::findOrFail($payment->account);
                $oldAccount->credit -= $payment->amount;
                $oldAccount->balance += $payment->amount;
                $oldAccount->save();

                $newAccount = accounts::findOrFail($validatedData['account']);
                $newAccount->credit += $validatedData['amount'];
                $newAccount->balance -= $validatedData['amount'];
                $newAccount->save();
            } else {
                $account = accounts::findOrFail($validatedData['account']);
                $account->credit += $amountDifference;
                $account->balance -= $amountDifference;
                $account->save();
            }

            $payment->update([
                'supplier' => $validatedData['supplier'],
                'account' => $validatedData['account'],
                'amount' => $validatedData['amount'],
                'date' => $validatedData['date'],
                'details' => $validatedData['details'],
            ]);

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'تعديل',
                'details' => 'تعديل سند دفع - المورد: ' . Suppliers::find($validatedData['supplier'])->supplier . ', الحساب: ' . accounts::find($validatedData['account'])->account . ', المبلغ: ' . $validatedData['amount'],
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم تعديل السند بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل السند.');
        }
    }

    public function show(string $id)
    {
        // تنفيذ الكود هنا
    }

    public function edit(string $id)
    {
        // تنفيذ الكود هنا
    }

    public function update(Request $request, string $id)
    {
        // تنفيذ الكود هنا
    }

    public function destroy(string $id)
    {
        try {
            $payment = payment::findOrFail($id);

            $supplier = Suppliers::findOrFail($payment->supplier);
            $supplier->debt -= $payment->amount;
            $supplier->balance -= $payment->amount;
            $supplier->save();

            $account = accounts::findOrFail($payment->account);
            $account->credit -= $payment->amount;
            $account->balance += $payment->amount;
            $account->save();

            $payment->delete();

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'حذف',
                'details' => 'حذف سند دفع - المورد: ' . $supplier->supplier . ', الحساب: ' . $account->account . ', المبلغ: ' . $payment->amount,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم حذف السند بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف السند.');
        }
    }

    public function printpay($id)
    {
        try {
            $suppliers = Suppliers::all();
            $accounts = accounts::all();
            $pay = payment::with(['supplier', 'account'])->findOrFail($id);
            return view('print.print_pay', compact('pay', 'suppliers', 'accounts'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء طباعة السند.');
        }
    }
}