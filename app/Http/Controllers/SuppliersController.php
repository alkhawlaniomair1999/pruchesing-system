<?php
namespace App\Http\Controllers;

use App\Models\Suppliers;
use App\Models\Accounts;
use App\Models\Branch;
use App\Models\sys_procs;
use App\Models\SupplyDetail;
use App\Models\FinancialOperation;
use App\Models\SystemOperation;
use Illuminate\Http\Request;
use Exception;

class SuppliersController extends Controller
{
    public function index()
    {
        try {
            $suppliers = Suppliers::all();
            $accounts = Accounts::all();
            $Branch = Branch::all();
            $proc = SupplyDetail::all();

            return view('supply', compact('suppliers', 'accounts', 'Branch', 'proc'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب البيانات.');
        }
    }

    public function pay()
    {
        try {
            $suppliers = Suppliers::all();
            $accounts = Accounts::all();
            $Branch = Branch::all();
            $proc = SupplyDetail::all();

            return view('pay', compact('suppliers', 'accounts', 'Branch', 'proc'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب البيانات.');
        }
    }

    public function create()
    {
        // تنفيذ الكود هنا
    }

    public function store(Request $request)
    {
        try {
            $data['supplier'] = $request->supplier;
            $data['debt'] = $request->debt;
            $data['credit'] = $request->credit;
            $data['balance'] = $request->debt - $request->credit;
            $supplier = Suppliers::create($data);

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'اضافة ',
                'details' => 'اضافة مورد جديد: ' . $supplier->supplier,
            ]);

            return redirect()->back()->with('success', 'تم إضافة المورد بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة المورد.');
        }
    }

    public function show(Suppliers $suppliers)
    {
        // تنفيذ الكود هنا
    }

    public function edit(Suppliers $suppliers)
    {
        // تنفيذ الكود هنا
    }

    public function update(Request $request, Suppliers $suppliers)
    {
        try {
            $s1['supplier'] = $request->newName;
            Suppliers::where('id', $request->id)->update($s1);

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'تعديل ',
                'details' => 'تعديل مورد: ' . $request->newName,
            ]);

            return redirect()->back()->with('success', 'تم تعديل المورد بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل المورد.');
        }
    }

    public function destroy($id)
    {
        try {
            $supplier = Suppliers::findOrFail($id);
            $supplierName = $supplier->supplier;
            $supplier->delete();

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'حذف ',
                'details' => 'حذف مورد: ' . $supplierName,
            ]);

            return redirect()->back()->with('success', 'تم حذف المورد بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المورد.');
        }
    }

    public function storeSupply(Request $request)
    {
        try {
            $supply = new SupplyDetail();
            $supply->supplier_id = $request->supplier_id;
            $supply->amount = $request->amount;
            $supply->payment_type = $request->payment_type;
            $supply->details = $request->details;
            $supply->date = $request->date;

            if ($request->payment_type == 'cash') {
                $account = Accounts::where('id', $request->account_name)->first();
                $branch = Branch::where('id', $account->branch_id)->first();
                $balanceBefore = $account->balance;

                $account->credit += $request->amount;
                $account->balance -= $request->amount;
                $account->save();

                FinancialOperation::create([
                    'related_id' => $account->id,
                    'related_type' => 'Account',
                    'operation_type' => 'توريد نقداً',
                    'debit' => $request->amount,
                    'credit' => 0,
                    'balance' => $account->balance,
                    'details' => 'توريد من المورد: ' . Suppliers::find($request->supplier_id)->supplier .
                                 ', من الحساب: ' . $account->name .
                                 ', الفرع: ' . $branch->name,
                    'user_id' => auth()->id(),
                ]);

                $supply->account_name = $request->account_name;
            } else if ($request->payment_type == 'credit') {
                $supplier = Suppliers::find($request->supplier_id);
                $supplier->credit += $request->amount;
                $supplier->balance -= $request->amount;
                $supplier->save();

                $supply->account_name = null;

                FinancialOperation::create([
                    'related_id' => $supplier->id,
                    'related_type' => 'Supplier',
                    'operation_type' => 'توريد آجلاً',
                    'debit' => 0,
                    'credit' => $request->amount,
                    'balance' => $supplier->balance,
                    'details' => 'توريد آجلاً من المورد: ' . $supplier->supplier,
                    'user_id' => auth()->id(),
                ]);
            }

            $supply->save();
            $newAccount = Accounts::where('id',$request->account_name)->first();
            $branchs = Branch::where('id',$newAccount->branch_id)->first();
            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'إضافة',
                'details' => 'إضافة عملية توريد - المورد: ' . Suppliers::find($request->supplier_id)->supplier .
                             ', المبلغ: ' . $request->amount .
                             ', نوع الدفع: ' . $request->payment_type .
                             ($request->payment_type == 'cash' ? ', من الحساب: ' . $newAccount->account . ', الفرع: ' . $branchs->branch : ''),
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم إضافة عملية التوريد بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة عملية التوريد.');
        }
    }

    public function updateSupply(Request $request)
    {
        try {
            $supply = SupplyDetail::findOrFail($request->id);

            $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'amount' => 'required|numeric|min:0',
                'payment_type' => 'required|in:cash,credit',
                'details' => 'nullable|string',
                'account_name' => 'required_if:payment_type,cash|exists:accounts,id',
            ]);

            // استرجاع المورد والحساب السابقين
            $previousSupplier = Suppliers::findOrFail($supply->supplier_id);
            $previousAccount = $supply->payment_type == 'cash' ? Accounts::findOrFail($supply->account_name) : null;

            // إعادة السجلات السابقة
            if ($supply->payment_type == 'cash' && $previousAccount) {
                $previousAccount->credit -= $supply->amount;
                $previousAccount->balance += $supply->amount;
                $previousAccount->save();
            } elseif ($supply->payment_type == 'credit') {
                $previousSupplier->balance += $supply->amount;
                $previousSupplier->credit -= $supply->amount;
                $previousSupplier->save();
            }

            // تحديث السجلات الجديدة
            if ($request->payment_type == 'cash') {
                $newAccount = Accounts::findOrFail($request->account_name);
                $branch = Branch::where('id', $newAccount->branch_id)->first();
                $newAccount->credit += $request->amount;
                $newAccount->balance -= $request->amount;
                $newAccount->save();
            } elseif ($request->payment_type == 'credit') {
                $newSupplier = Suppliers::findOrFail($request->supplier_id);
                $newSupplier->balance -= $request->amount;
                $newSupplier->credit += $request->amount;
                $newSupplier->save();
            }

            // تحديث تفاصيل التوريد
            $supply->supplier_id = $request->supplier_id;
            $supply->amount = $request->amount;
            $supply->payment_type = $request->payment_type;
            $supply->details = $request->details;
            $supply->account_name = $request->payment_type == 'cash' ? $request->account_name : null;
            $supply->save();

            // إنشاء سجل العملية المالية
            FinancialOperation::create([
                'related_id' => $request->payment_type == 'cash' ? $newAccount->id : $newSupplier->id,
                'related_type' => $request->payment_type == 'cash' ? 'Account' : 'Supplier',
                'operation_type' => $request->payment_type == 'cash' ? 'تعديل توريد نقداً' : 'تعديل توريد آجلاً',
                'debit' => $request->payment_type == 'cash' ? $request->amount : 0,
                'credit' => $request->payment_type == 'credit' ? $request->amount : 0,
                'balance' => $request->payment_type == 'cash' ? $newAccount->balance : $newSupplier->balance,
                'details' => 'تعديل توريد من المورد: ' . Suppliers::find($request->supplier_id)->supplier .
                             ($request->payment_type == 'cash' ? ', من الحساب: ' . $newAccount->name . ', الفرع: ' . $branch->name : ''),
                'user_id' => auth()->id(),
            ]);
            $newAccount = Accounts::where('id',$request->account_name)->first();
            $branchs = Branch::where('id',$newAccount->branch_id)->first();

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'تعديل',
                'details' => 'تعديل عملية توريد - المورد: ' . Suppliers::find($request->supplier_id)->supplier .
                             ', المبلغ: ' . $request->amount .
                             ', نوع الدفع: ' . $request->payment_type .
                             ($request->payment_type == 'cash' ? ', من الحساب: ' . $newAccount->account . ', الفرع: ' . $branchs->branch : ''),
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم تعديل بيانات التوريد بنجاح.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل بيانات التوريد.');
        }
    }

    public function deleteSupply($id)
    {
        try {
            $supply = SupplyDetail::find($id);

            if ($supply->payment_type == 'cash') {
                $account = Accounts::where('id', $supply->account_name)->first();

                $account->credit -= $supply->amount;
                $account->balance += $supply->amount;
                $account->save();

                FinancialOperation::where('related_id', $account->id)
                    ->where('related_type', 'Account')
                    ->where('operation_type', 'توريد نقداً')
                    ->delete();
            } else if ($supply->payment_type == 'credit') {
                $supplier = Suppliers::find($supply->supplier_id);

                $supplier->credit -= $supply->amount;
                $supplier->balance += $supply->amount;
                $supplier->save();
            }

            $supply->delete();

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'حذف',
                'details' => 'حذف عملية توريد - المورد: ' . Suppliers::find($supply->supplier_id)->supplier .
                             ', المبلغ: ' . $supply->amount .
                             ', نوع الدفع: ' . $supply->payment_type,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم حذف عملية التوريد بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف عملية التوريد.');
        }
    }

    public function printSupply($id)
    {
        try {
            $supply = SupplyDetail::with(['supplier', 'account'])->findOrFail($id);
            return view('print.print_supply', compact('supply'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء طباعة بيانات التوريد.');
        }
    }
}