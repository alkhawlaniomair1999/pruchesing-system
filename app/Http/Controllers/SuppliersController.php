<?php
namespace App\Http\Controllers;

use App\Models\Suppliers;
use App\Models\accounts;
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
            $accounts = accounts::all();
            $Branch = Branch::all();
            $proc = SupplyDetail::all();

            SystemOperation::create([
                'user_id' => auth('web')->id(),
                'operation_type' => 'عرض ',
                'details' => 'عرض صفحة قائمة التوريد',
            ]);

            return view('supply', compact('suppliers', 'accounts', 'Branch', 'proc'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب البيانات.');
        }
    }

    public function pay()
    {
        try {
            $suppliers = Suppliers::all();
            $accounts = accounts::all();
            $Branch = Branch::all();
            $proc = SupplyDetail::all();

            SystemOperation::create([
                'user_id' => auth('web')->id(),
                'operation_type' => 'عرض ',
                'details' => 'عرض صفحة السندات',
            ]);

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
            Suppliers::create($data);

            SystemOperation::create([
                'user_id' => auth('web')->id(),
                'operation_type' => 'اضافة ',
                'details' => 'اضافة مورد جديد',
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
                'user_id' => auth('web')->id(),
                'operation_type' => 'تعديل ',
                'details' => 'تعديل مورد',
            ]);

            return redirect()->back()->with('success', 'تم تعديل المورد بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل المورد.');
        }
    }

    public function destroy($id)
    {
        try {
            Suppliers::where('id', $id)->delete();

            SystemOperation::create([
                'user_id' => auth('web')->id(),
                'operation_type' => 'حذف ',
                'details' => 'حذف مورد',
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
                $balanceBefore = $account->balance;

                $account->credit += $request->amount;
                $account->balance -= $request->amount;
                $account->save();

                $supplier = Suppliers::where('id', $request->supplier_id)->first();
                $supplier->debt += $request->amount;
                $supplier->credit += $request->amount;
                $supplier->save();

                FinancialOperation::create([
                    'related_id' => $account->id,
                    'related_type' => 'Account',
                    'operation_type' => 'توريد نقداً',
                    'debit' => $request->amount,
                    'credit' => 0,
                    'balance' => $account->balance,
                    'details' => 'توريد من المورد ID: ' . $request->supplier_id,
                    'user_id' => auth('web')->id(),
                ]);

                $supply->account_name = $request->account_name;
            } else if ($request->payment_type == 'credit') {
                $supplier = Suppliers::find($request->supplier_id);
                $supplier->debt += $request->amount;
                $supplier->balance += $request->amount;
                $supplier->save();

                $supply->account_name = null;

                FinancialOperation::create([
                    'related_id' => $supplier->id,
                    'related_type' => 'Supplier',
                    'operation_type' => 'توريد آجلاً',
                    'debit' => 0,
                    'credit' => $request->amount,
                    'balance' => $supplier->balance,
                    'details' => 'توريد آجلاً',
                    'user_id' => auth('web')->id(),
                ]);
            }

            $supply->save();

            SystemOperation::create([
                'user_id' => auth('web')->id(),
                'operation_type' => 'إضافة',
                'details' => 'إضافة عملية توريد - المورد: ' . $request->supplier_id .
                             ', المبلغ: ' . $request->amount .
                             ', نوع الدفع: ' . $request->payment_type,
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

            $supply->supplier_id = $request->supplier_id;
            $supply->details = $request->details;
            $supply->date = $request->date;

            if ($supply->payment_type == $request->payment_type) {
                if ($request->payment_type == 'cash') {
                    if ($supply->account_name == $request->account_name) {
                        $account = Accounts::findOrFail($request->account_name);
                        $balanceBefore = $account->balance;

                        $account->credit -= $supply->amount;
                        $account->balance += $supply->amount;
                        $account->credit += $request->amount;
                        $account->balance -= $request->amount;
                        $account->save();
                    } else {
                        $account = Accounts::findOrFail($supply->account_name);
                        $balanceBefore = $account->balance;
                        $account->credit -= $supply->amount;
                        $account->balance += $supply->amount;
                        $account->save();
                        $account = Accounts::findOrFail($request->account_name);
                        $account->credit += $request->amount;
                        $account->balance -= $request->amount;
                        $account->save();
                    }

                    if ($supply->supplier_id == $request->supplier_id) {
                        $supplier = Suppliers::findOrFail($request->supplier_id);
                        $supplier->debt -= $supply->amount;
                        $supplier->credit -= $supply->amount;
                        $supplier->debt += $request->amount;
                        $supplier->credit += $request->amount;
                        $supplier->save();
                    } else {
                        $supplier = Suppliers::findOrFail($supply->supplier_id);
                        $supplier->debt -= $supply->amount;
                        $supplier->credit -= $supply->amount;
                        $supplier->save();
                        $supplier = Suppliers::findOrFail($request->supplier_id);
                        $supplier->debt += $request->amount;
                        $supplier->credit += $request->amount;
                        $supplier->save();
                    }

                    FinancialOperation::create([
                        'related_id' => $account->id,
                        'related_type' => 'Account',
                        'operation_type' => 'تعديل توريد نقداً',
                        'debit' => $request->amount,
                        'credit' => 0,
                        'balance' => $account->balance,
                        'details' => 'تعديل توريد من المورد ID: ' . $request->supplier_id,
                        'user_id' => auth('web')->id(),
                    ]);

                    $supply->account_name = $request->account_name;
                } else if ($request->payment_type == 'credit') {
                    if ($supply->supplier_id == $request->supplier_id) {
                        $supplier = Suppliers::findOrFail($request->supplier_id);
                        $supplier->debt -= $supply->amount;
                        $supplier->credit -= $supply->amount;
                        $supplier->debt += $request->amount;
                        $supplier->credit += $request->amount;
                        $supplier->save();
                    } else {
                        $supplier = Suppliers::findOrFail($supply->supplier_id);
                        $supplier->debt -= $supply->amount;
                        $supplier->credit -= $supply->amount;
                        $supplier->save();
                        $supplier = Suppliers::findOrFail($request->supplier_id);
                        $supplier->debt += $request->amount;
                        $supplier->credit += $request->amount;
                        $supplier->save();
                    }

                    $supply->account_name = null;

                    FinancialOperation::create([
                        'related_id' => $supplier->id,
                        'related_type' => 'Supplier',
                        'operation_type' => 'تعديل توريد آجلاً',
                        'debit' => 0,
                        'credit' => $request->amount,
                        'balance' => $supplier->balance,
                        'details' => 'تعديل توريد آجلاً',
                        'user_id' => auth('web')->id(),
                        

                    ]);
                }
            } elseif ($request->payment_type == 'cash') {
                $account = Accounts::findOrFail($request->account_name);
                $account->credit += $request->amount;
                $account->balance -= $request->amount;
                $account->save();

                if ($supply->supplier_id == $request->supplier_id) {
                    $supplier = Suppliers::findOrFail($request->supplier_id);
                    $supplier->credit -= $supply->amount;
                    $supplier->debt += $request->amount;
                    $supplier->credit += $request->amount;
                    $supplier->balance += $request->amount;
                    $supplier->save();
                } else {
                    $supplier = Suppliers::findOrFail($supply->supplier_id);
                    $supplier->debt -= $supply->amount;
                    $supplier->credit -= $supply->amount;
                    $supplier->save();
                    $supplier = Suppliers::findOrFail($request->supplier_id);
                    $supplier->debt += $request->amount;
                    $supplier->credit += $request->amount;
                    $supplier->save();
                }

                FinancialOperation::create([
                    'related_id' => $account->id,
                    'related_type' => 'Account',
                    'operation_type' => 'تعديل توريد نقداً',
                    'debit' => $request->amount,
                    'credit' => 0,
                    'balance' => $account->balance,
                    'details' => 'تعديل توريد من المورد ID: ' . $request->supplier_id,
                    'user_id' => auth('web')->id(),
                ]);

                $supply->account_name = $request->account_name;
            } else {
                $account = Accounts::findOrFail($supply->account_name);
                $balanceBefore = $account->balance;
                $account->credit -= $supply->amount;
                $account->balance += $supply->amount;
                $account->save();

                if ($supply->supplier_id == $request->supplier_id) {
                    $supplier = Suppliers::findOrFail($request->supplier_id);
                    $supplier->debt -= $supply->amount;
                    $supplier->credit -= $supply->amount;
                    $supplier->credit += $request->amount;
                    $supplier->balance -= $request->amount;
                    $supplier->save();
                } else {
                    $supplier = Suppliers::findOrFail($supply->supplier_id);
                    $supplier->debt -= $supply->amount;
                    $supplier->credit -= $supply->amount;
                    $supplier->save();
                    $supplier = Suppliers::findOrFail($request->supplier_id);
                    $supplier->debt += $request->amount;
                    $supplier->credit += $request->amount;
                    $supplier->save();
                }

                FinancialOperation::create([
                    'related_id' => $account->id,
                    'related_type' => 'Account',
                    'operation_type' => 'تعديل توريد اجل',
                    'debit' => $request->amount,
                    'credit' => 0,
                    'balance' => $account->balance,
                    'details' => 'تعديل توريد من المورد ID: ' . $request->supplier_id,
                    'user_id' => auth('web')->id(),
                ]);

                $supply->account_name = null;
            }

            $supply->amount = $request->amount;
            $supply->payment_type = $request->payment_type;
            $supply->save();

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

                $supplier = Suppliers::find($supply->supplier_id);
                $supplier->debt -= $supply->amount;
                $supplier->credit -= $supply->amount;
                $supplier->save();

                FinancialOperation::where('related_id', $account->id)
                    ->where('related_type', 'Account')
                    ->where('operation_type', 'توريد نقداً')
                    ->delete();
            } else if ($supply->payment_type == 'credit') {
                $supplier = Suppliers::find($supply->supplier_id);

                $supplier->debt -= $supply->amount;
                $supplier->balance -= $supply->amount;
                $supplier->save();
            }

            $supply->delete();

            return redirect()->back()->with('success', 'تم حذف عملية التوريد بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف عملية التوريد.');
        }
    }

    public function printSupply($id)
    {
        try {
            $supply = SupplyDetail::find($id);
            if ($supply->payment_type == 'cash') {
                $account = accounts::find($supply->account_name);
                return view('print.print_supply', compact('supply','account'));
            }else{
                return view('print.print_supply', compact('supply'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء طباعة بيانات التوريد.');
        }
    }
}