<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Suppliers;
use App\Models\accounts;
use App\Models\Branch;
use App\Models\sys_procs;
use App\Models\SupplyDetail;
use App\Models\FinancialOperation;
use App\Models\SystemOperation;



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
        $proc=SupplyDetail::all();
        return view('supply',compact('suppliers','accounts','Branch','proc'));
    }
    public function pay()
    {
        $suppliers=Suppliers::all();
        $accounts=accounts::all();
        $Branch=Branch::all();
        $proc=SupplyDetail::all();
        return view('pay',compact('suppliers','accounts','Branch','proc'));
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



// yazeeeeed


public function storeSupply(Request $request)
{
    $supply = new SupplyDetail();
    $supply->supplier_id = $request->supplier_id;
    $supply->amount = $request->amount;
    $supply->payment_type = $request->payment_type;
    $supply->details = $request->details;
    $supply->date = $request->date;

    if ($request->payment_type == 'cash') {
        $account = Accounts::where('id', $request->account_name)->first();
        $balanceBefore = $account->balance;

        // تحديث الحساب
        $account->credit += $request->amount;
        $account->balance -= $request->amount;
        $account->save();
        // تحديث حساب المورد
        $supplier = Suppliers::where('id',$request->supplier_id)->first();
        $supplier->debt += $request->amount;
        $supplier->credit += $request->amount;
        $supplier->save();

        // تسجيل العملية في جدول "العمليات المالية"
        FinancialOperation::create([
            'related_id' => $account->id,
            'related_type' => 'Account',
            'operation_type' => 'توريد نقداً',
            'debit' => $request->amount,
            'credit' => 0,
            'balance' => $account->balance,
            'details' => 'توريد من المورد ID: ' . $request->supplier_id,
            'user_id' => auth()->id(),
        ]);

        $supply->account_name = $request->account_name;
    } else if ($request->payment_type == 'credit') {
        $supplier = Suppliers::find($request->supplier_id);
        $supplier->debt += $request->amount;
        $supplier->balance += $request->amount;
        $supplier->save();

        $supply->account_name = null;

        // تسجيل العملية في جدول "العمليات المالية"
        FinancialOperation::create([
            'related_id' => $supplier->id,
            'related_type' => 'Supplier',
            'operation_type' => 'توريد آجلاً',
            'debit' => 0,
            'credit' => $request->amount,
            'balance' => $supplier->balance,
            'details' => 'توريد آجلاً',
            'user_id' => auth()->id(),
        ]);
    }

    $supply->save();

    return redirect()->back();
}


public function updateSupply(Request $request)
{
    $supply = SupplyDetail::findOrFail($request->id);

    // التحقق من المدخلات
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'amount' => 'required|numeric|min:0',
        'payment_type' => 'required|in:cash,credit',
        'details' => 'nullable|string',
        'account_name' => 'required_if:payment_type,cash|exists:accounts,id',
    ]);

    // تحديث بيانات التوريد
    $supply->supplier_id = $request->supplier_id;
    $supply->details = $request->details;
    $supply->date = $request->date;
    if($supply->payment_type == $request->payment_type){
    if ($request->payment_type == 'cash') {
        if($supply->account_name == $request->account_name){
        $account = Accounts::findOrFail($request->account_name);
        $balanceBefore = $account->balance;

        // تحديث الحساب
        $account->credit -= $supply->amount; // إزالة القيمة القديمة
        $account->balance += $supply->amount; // استعادة الرصيد القديم
        $account->credit += $request->amount; // إضافة القيمة الجديدة
        $account->balance -= $request->amount; // خصم القيمة الجديدة
        $account->save();
        }
        else{
            $account = Accounts::findOrFail($supply->account_name);
            $balanceBefore = $account->balance;
            $account->credit -= $supply->amount; // إزالة القيمة القديمة
            $account->balance += $supply->amount; 
            $account->save();
            $account = Accounts::findOrFail($request->account_name);
            $account->credit += $request->amount; // إضافة القيمة الجديدة
            $account->balance -= $request->amount; // خصم القيمة الجديدة
            $account->save();
        }
        // تحديث المورد
        if($supply->supplier_id == $request->supplier_id){
        $supplier = Suppliers::findOrFail($request->supplier_id);
        $supplier->debt -= $supply->amount; // إزالة القيمة القديمة
        $supplier->credit -= $supply->amount; // إزالة القيمة القديمة
        $supplier->debt += $request->amount; // إضافة القيمة الجديدة
        $supplier->credit += $request->amount; // إضافة القيمة الجديدة
        $supplier->save();
        }
        else{
            $supplier = Suppliers::findOrFail($supply->supplier_id);
            $supplier->debt -= $supply->amount; // إزالة القيمة القديمة
            $supplier->credit -= $supply->amount; // إزالة القيمة القديمة
            $supplier->save();
            $supplier = Suppliers::findOrFail($request->supplier_id);
            $supplier->debt += $request->amount; // إضافة القيمة الجديدة
            $supplier->credit += $request->amount; // إضافة القيمة الجديدة
            $supplier->save();
        }
        // تسجيل العملية في جدول "العمليات المالية"
        FinancialOperation::create([
            'related_id' => $account->id,
            'related_type' => 'Account',
            'operation_type' => 'تعديل توريد نقداً',
            'debit' => $request->amount,
            'credit' => 0,
            'balance' => $account->balance,
            'details' => 'تعديل توريد من المورد ID: ' . $request->supplier_id,
            'user_id' => auth()->id(),
        ]);

        $supply->account_name = $request->account_name;
    } else if ($request->payment_type == 'credit') {
        if($supply->supplier_id == $request->supplier_id){
            $supplier = Suppliers::findOrFail($request->supplier_id);
            $supplier->debt -= $supply->amount; // إزالة القيمة القديمة
            $supplier->credit -= $supply->amount; // إزالة القيمة القديمة
            $supplier->debt += $request->amount; // إضافة القيمة الجديدة
            $supplier->credit += $request->amount; // إضافة القيمة الجديدة
            $supplier->save();
            }
            else{
                $supplier = Suppliers::findOrFail($supply->supplier_id);
                $supplier->debt -= $supply->amount; // إزالة القيمة القديمة
                $supplier->credit -= $supply->amount; // إزالة القيمة القديمة
                $supplier->save();
                $supplier = Suppliers::findOrFail($request->supplier_id);
                $supplier->debt += $request->amount; // إضافة القيمة الجديدة
                $supplier->credit += $request->amount; // إضافة القيمة الجديدة
                $supplier->save();
            }

        $supply->account_name = null;

        // تسجيل العملية في جدول "العمليات المالية"
        FinancialOperation::create([
            'related_id' => $supplier->id,
            'related_type' => 'Supplier',
            'operation_type' => 'تعديل توريد آجلاً',
            'debit' => 0,
            'credit' => $request->amount,
            'balance' => $supplier->balance,
            'details' => 'تعديل توريد آجلاً',
            'user_id' => auth()->id(),
        ]);
    }
    }
    elseif($request->payment_type == 'cash'){
        $account = Accounts::findOrFail($request->account_name);
        $account->credit += $request->amount; // إضافة القيمة الجديدة
        $account->balance -= $request->amount; // خصم القيمة الجديدة
        $account->save();
           // تحديث المورد
           if($supply->supplier_id == $request->supplier_id){
            $supplier = Suppliers::findOrFail($request->supplier_id);
            $supplier->debt -= $supply->amount; // إزالة القيمة القديمة
            $supplier->credit -= $supply->amount; // إزالة القيمة القديمة
            $supplier->debt += $request->amount; // إضافة القيمة الجديدة
            $supplier->credit += $request->amount; // إضافة القيمة الجديدة
            $supplier->save();
            }
            else{
                $supplier = Suppliers::findOrFail($supply->supplier_id);
                $supplier->debt -= $supply->amount; // إزالة القيمة القديمة
                $supplier->credit -= $supply->amount; // إزالة القيمة القديمة
                $supplier->save();
                $supplier = Suppliers::findOrFail($request->supplier_id);
                $supplier->debt += $request->amount; // إضافة القيمة الجديدة
                $supplier->credit += $request->amount; // إضافة القيمة الجديدة
                $supplier->save();
            }
            // تسجيل العملية في جدول "العمليات المالية"
            FinancialOperation::create([
                'related_id' => $account->id,
                'related_type' => 'Account',
                'operation_type' => 'تعديل توريد نقداً',
                'debit' => $request->amount,
                'credit' => 0,
                'balance' => $account->balance,
                'details' => 'تعديل توريد من المورد ID: ' . $request->supplier_id,
                'user_id' => auth()->id(),
            ]);
    
            $supply->account_name = $request->account_name;
    }
    else{
        $account = Accounts::findOrFail($supply->account_name);
            $balanceBefore = $account->balance;
            $account->credit -= $supply->amount; // إزالة القيمة القديمة
            $account->balance += $supply->amount; 
            $account->save();
             // تحديث المورد
        if($supply->supplier_id == $request->supplier_id){
            $supplier = Suppliers::findOrFail($request->supplier_id);
            $supplier->debt -= $supply->amount; // إزالة القيمة القديمة
            $supplier->credit -= $supply->amount; // إزالة القيمة القديمة
            $supplier->debt += $request->amount; // إضافة القيمة الجديدة
            $supplier->credit += $request->amount; // إضافة القيمة الجديدة
            $supplier->save();
            }
            else{
                $supplier = Suppliers::findOrFail($supply->supplier_id);
                $supplier->debt -= $supply->amount; // إزالة القيمة القديمة
                $supplier->credit -= $supply->amount; // إزالة القيمة القديمة
                $supplier->save();
                $supplier = Suppliers::findOrFail($request->supplier_id);
                $supplier->debt += $request->amount; // إضافة القيمة الجديدة
                $supplier->credit += $request->amount; // إضافة القيمة الجديدة
                $supplier->save();
            }
            // تسجيل العملية في جدول "العمليات المالية"
            FinancialOperation::create([
                'related_id' => $account->id,
                'related_type' => 'Account',
                'operation_type' => 'تعديل توريد نقداً',
                'debit' => $request->amount,
                'credit' => 0,
                'balance' => $account->balance,
                'details' => 'تعديل توريد من المورد ID: ' . $request->supplier_id,
                'user_id' => auth()->id(),
            ]);
    
            $supply->account_name = $request->account_name;
    }
    // تحديث بيانات التوريد
    $supply->amount = $request->amount;
    $supply->payment_type = $request->payment_type;
    $supply->save();

    return redirect()->back()->with('success', 'تم تعديل بيانات التوريد بنجاح.');
}




public function deleteSupply($id)
{
    $supply = SupplyDetail::find($id);

    if ($supply->payment_type == 'cash') {
        $account = Accounts::where('account', $supply->account_name)->first();

        // تحديث الحساب
        $account->balance -= $supply->amount;
        $account->save();

        // حذف العملية من جدول "العمليات المالية"
        FinancialOperation::where('related_id', $account->id)
            ->where('related_type', 'Account')
            ->where('operation_type', 'توريد نقداً')
            ->delete();
    } else if ($supply->payment_type == 'credit') {
        $supplier = Suppliers::find($supply->supplier_id);

        // تحديث المورد
        $supplier->balance -= $supply->amount;
        $supplier->save();
    }

    $supply->delete();

    return redirect()->back();}




















































    // public function det(Request $request)
    // {
    //     $supplier = Suppliers::where('id', $request->supplier)->first();
    //     $sp['debt'] = $supplier->debt + $request->price;
    //     $sp['balance'] = $supplier->balance + $request->price;
    //     Suppliers::where('id',$request->supplier)->update($sp);
    //     $pro['detail'] = $request->detail;
    //     $pro['proc_type'] = 'supp';
    //     $pro['acc_type'] = 'supplier';
    //     $pro['account_id'] = $request->supplier;
    //     $pro['debt'] = $request->price;
    //     $pro['date'] = $request->date;
    //     if ($request->paymentType == 'cash') {
    //         $account = accounts::where('id', $request->account)->first();
    //         $ac['credit'] = $account->credit + $request->price;
    //         $ac['balance'] = $account->balance - $request->price;
    //         accounts::where('id', $request->account)->update($ac);
    //         $proc['detail'] = $request->detail;
    //         $proc['proc_type'] = 'supp';
    //         $proc['acc_type'] = 'account';
    //         $proc['account_id'] = $request->account;
    //         $proc['debt'] = 0;
    //         $proc['credit'] = $request->price;
    //         $proc['balance'] = $ac['balance'];
    //         $proc['date'] = $request->date;
    //         sys_procs::create($proc);
    //         $supplier = Suppliers::where('id', $request->supplier)->first();
    //         $sp['credit'] = $supplier->credit + $request->price;
    //         $sp['balance'] = $supplier->balance - $request->price;
    //         Suppliers::where('id',$request->supplier)->update($sp);
    //         $pro['credit'] = $request->price;
    //         $pro['balance'] = $sp['balance'];
    //         sys_procs::create($pro);
    //     }
    //     else {
    //         $pro['credit'] = 0;
    //         $pro['balance'] = $sp['balance'];
    //         sys_procs::create($pro);
    //     }
    //     return redirect()->back();

    // }
    // public function det2(Request $request)
    // {
    //     $supplier = Suppliers::where('id', $request->supplier)->first();
    //     $sp['credit'] = $supplier->credit + $request->price;
    //     $sp['balance'] = $supplier->balance - $request->price;
    //     Suppliers::where('id',$request->supplier)->update($sp);
    //     $pro['detail'] = $request->detail;
    //     $pro['proc_type'] = 'pay';
    //     $pro['acc_type'] = 'supplier';
    //     $pro['account_id'] = $request->supplier;
    //     $pro['debt'] = 0;
    //     $pro['date'] = $request->date;
    //     $pro['credit'] = $request->price;
    //     $pro['balance'] = $sp['balance'];
    //     sys_procs::create($pro);
    //     $account = accounts::where('id',$request->account)->first();
    //     $ac['credit'] = $account->credit + $request->price;
    //     $ac['balance'] = $account->balance - $request->price;
    //     accounts::where('id',$request->account)->update($ac);
    //     $proc['detail'] = $request->detail;
    //     $proc['proc_type'] = 'pay';
    //     $proc['acc_type'] = 'account';
    //     $proc['account_id'] = $request->account;
    //     $proc['debt'] = 0;
    //     $proc['credit'] = $request->price;
    //     $proc['balance'] = $ac['balance'];
    //     $proc['date'] = $request->date;
    //     sys_procs::create($proc);
    //     return redirect()->back();

    



    
}
