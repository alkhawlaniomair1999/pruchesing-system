<?php
namespace App\Http\Controllers;

use App\Models\details;
use App\Models\accounts;
use App\Models\Branch;
use App\Models\items;
use App\Models\Suppliers;
use App\Models\SystemOperation;
use Illuminate\Http\Request;
use Exception;

class DetailsController extends Controller
{
    public function index()
    {
        try {
            $items = items::all();
            $Branch = Branch::all();
            $accounts = accounts::all();
            $details = details::all();
            $suppliers = Suppliers::all();
            return view('details', ['items' => $items, 'suppliers'=> $suppliers, 'Branch' => $Branch, 'accounts' => $accounts, 'details' => $details]);
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
        try{
            $details['detail'] = $request->detail;
            $details['total'] = $request->totalPrice;
            $details['item_id'] = $request->item;
            $details['branch_id'] = $request->branch;
            $details['payment_method'] = $request->payment_method;
            if ($request->payment_method == "cash") {
                $details['account_id'] = $request->account;

            } else {
                $details['account_id'] = $request->supplier;
            }
            $details['date'] = $request->date;
            $details['tax'] = $request->tax;
            if ($request->tax == "True") {
                $x = $request->totalPrice / 1.15;
                $details['price'] = $x;
            } else {
                $details['price'] = $request->totalPrice;
            }
            details::create($details);
            if($request->payment_method == "cash"){
            $acc = accounts::where('id', $request->account)->first();
            $credit = $acc['credit'] + $request->totalPrice;
            $balance = $acc['balance'] - $request->totalPrice;
            accounts::where('id', $request->account)->update(['credit' => $credit, 'balance' => $balance]);
            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'إضافة',
                'details' => 'إضافة تفاصيل - العنصر: ' . items::find($request->item)->item . ', الفرع: ' . Branch::find($request->branch)->branch . ', الحساب: ' . $acc->account . ', المبلغ: ' . $request->totalPrice,
                'status' => 'successful',
            ]);
            }else{
                $s = Suppliers::where('id', $request->supplier)->first();
                $credit = $s['credit'] + $request->totalPrice;
                $balance = $s['balance'] - $request->totalPrice;
                Suppliers::where('id', $request->supplier)->update(['credit' => $credit, 'balance' => $balance]);
                SystemOperation::create([
                    'user_id' => auth()->id(),
                    'operation_type' => 'إضافة',
                    'details' => 'إضافة تفاصيل - العنصر: ' . items::find($request->item)->item . ', الفرع: ' . Branch::find($request->branch)->branch . ', الحساب: ' . $s->supplier . ', المبلغ: ' . $request->totalPrice,
                    'status' => 'successful',
                ]);
            }        
            return redirect()->back()->with('success', 'تم إنشاء التفاصيل بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء التفاصيل.');
        }

    }

    public function update(Request $request)
    {
        try {
            $det = details::where('id', $request->id)->first();
            if($det->payment_method == 'cash' && $request->payment_method == 'cash'){
                $ce = accounts::where('id', $det['account_id'])->first();
            if ($det['account_id'] == $request->account) {
                $c2 = $ce['credit'] - $det['total'] + $request->totalPrice;
                accounts::where('id', $ce['id'])->update(['credit' => $c2]);
                $acc = accounts::where('id', $ce['id'])->first();
                $balance = $acc['debt'] - $acc['credit'];
                accounts::where('id', $ce['id'])->update(['balance' => $balance]);
            } else {
                $c2 = $ce['credit'] - $det['total'];
                accounts::where('id', $ce['id'])->update(['credit' => $c2]);
                $ac = accounts::where('id', $ce['id'])->first();
                $balance = $ac['debt'] - $ac['credit'];
                accounts::where('id', $ce['id'])->update(['balance' => $balance]);
                $acc = accounts::where('id', $request->account)->first();
                $credit = $acc['credit'] + $request->totalPrice;
                accounts::where('id', $request->account)->update(['credit' => $credit]);
                $acc1 = accounts::where('id', $request->account)->first();
                $balance = $acc1['debt'] - $acc1['credit'];
                accounts::where('id', $request->account)->update(['balance' => $balance]);
            }
            } elseif ($det->payment_method == 'cash' && $request->payment_method != 'cash') {
                $ce = accounts::where('id', $det['account_id'])->first();
                $c2 = $ce['credit'] - $det['total'];
                accounts::where('id', $ce['id'])->update(['credit' => $c2]);
                $ac = accounts::where('id', $ce['id'])->first();
                $balance = $ac['debt'] - $ac['credit'];
                accounts::where('id', $ce['id'])->update(['balance' => $balance]);
                $acc = Suppliers::where('id', $request->supplier)->first();
                $credit = $acc['credit'] + $request->totalPrice;
                Suppliers::where('id', $request->supplier)->update(['credit' => $credit]);
                $acc1 = Suppliers::where('id', $request->supplier)->first();
                $balance = $acc1['debt'] - $acc1['credit'];
                Suppliers::where('id', $request->supplier)->update(['balance' => $balance]);
            } elseif ($det->payment_method != 'cash' && $request->payment_method == 'cash') {
                $ce = Suppliers::where('id', $det['account_id'])->first();
                $c2 = $ce['credit'] - $det['total'];
                Suppliers::where('id', $ce['id'])->update(['credit' => $c2]);
                $ac = Suppliers::where('id', $ce['id'])->first();
                $balance = $ac['debt'] - $ac['credit'];
                Suppliers::where('id', $ce['id'])->update(['balance' => $balance]);
                $acc = accounts::where('id', $request->account)->first();
                $credit = $acc['credit'] + $request->totalPrice;
                accounts::where('id', $request->account)->update(['credit' => $credit]);
                $acc1 = accounts::where('id', $request->account)->first();
                $balance = $acc1['debt'] - $acc1['credit'];
                accounts::where('id', $request->account)->update(['balance' => $balance]);
            } elseif ($det->payment_method != 'cash' && $request->payment_method != 'cash') {
                $ce = Suppliers::where('id', $det['account_id'])->first();
                if ($det['account_id'] == $request->supplier) {
                    $c2 = $ce['credit'] - $det['total'] + $request->totalPrice;
                    Suppliers::where('id', $ce['id'])->update(['credit' => $c2]);
                    $acc = Suppliers::where('id', $ce['id'])->first();
                    $balance = $acc['debt'] - $acc['credit'];
                    Suppliers::where('id', $ce['id'])->update(['balance' => $balance]);
                } else {
                    $c2 = $ce['credit'] - $det['total'];
                    Suppliers::where('id', $ce['id'])->update(['credit' => $c2]);
                    $ac = Suppliers::where('id', $ce['id'])->first();
                    $balance = $ac['debt'] - $ac['credit'];
                    Suppliers::where('id', $ce['id'])->update(['balance' => $balance]);
                    $acc = accounts::where('id', $request->account)->first();
                    $credit = $acc['credit'] + $request->totalPrice;
                    accounts::where('id', $request->account)->update(['credit' => $credit]);
                    $acc1 = accounts::where('id', $request->account)->first();
                    $balance = $acc1['debt'] - $acc1['credit'];
                    accounts::where('id', $request->account)->update(['balance' => $balance]);
                }
            }
            
            $details['detail'] = $request->detail;
            $details['total'] = $request->totalPrice;
            $details['item_id'] = $request->item;
            $details['branch_id'] = $request->branch;
            $details['payment_method'] = $request->payment_method;
            if ($request->payment_method == "cash") {
                $details['account_id'] = $request->account;
            } else {
                $details['account_id'] = $request->supplier;
            }
            $details['date'] = $request->date;
            $details['tax'] = $request->tax;
            if ($request->tax == "True") {
                $x = $request->totalPrice / 1.15;
                $details['price'] = $x;
            } else {
                $details['price'] = $request->totalPrice;
            }
            details::where('id', $request->id)->update($details);
            if ($request->payment_method == 'cash') {
                SystemOperation::create([
                    'user_id' => auth()->id(),
                    'operation_type' => 'تعديل',
                    'details' => 'تعديل تفاصيل - العنصر: ' . items::find($request->item)->item . ', الفرع: ' . Branch::find($request->branch)->branch . ', الحساب: ' . accounts::find($request->account)->account . ', المبلغ: ' . $request->totalPrice,
                    'status' => 'successful',
                ]);
                }else{
                    SystemOperation::create([
                        'user_id' => auth()->id(),
                        'operation_type' => 'تعديل',
                        'details' => 'تعديل تفاصيل - العنصر: ' . items::find($request->item)->item . ', الفرع: ' . Branch::find($request->branch)->branch . ', الحساب: ' . Suppliers::find($request->supplier)->supplier . ', المبلغ: ' . $request->totalPrice,
                        'status' => 'successful',
                    ]);
        
                }
          
            return redirect()->back()->with('success', 'تم تحديث التفاصيل بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث التفاصيل.');
        }
    }

    public function destroy($id)
    {
        try {
            $c1 = details::where('id', $id)->first();
            if($c1->payment_method == 'cash'){
                $ce = accounts::where('id', $c1['account_id'])->first();
                $c2 = $ce['credit'] - $c1['total'];
                $balance = $ce['balance'] + $c1['total'];
                accounts::where('id', $ce['id'])->update(['credit' => $c2]);
                $ce = accounts::where('id', $c1['account_id'])->first();
                $balance = $ce['debt'] - $ce['credit'];
                accounts::where('id', $ce['id'])->update(['balance' => $balance]);
            }
            else{
                $ce = Suppliers::where('id', $c1['account_id'])->first();
                $c2 = $ce['credit'] - $c1['total'];
                $balance = $ce['balance'] + $c1['total'];
                Suppliers::where('id', $ce['id'])->update(['credit' => $c2]);
                $ce = Suppliers::where('id', $c1['account_id'])->first();
                $balance = $ce['debt'] - $ce['credit'];
                Suppliers::where('id', $ce['id'])->update(['balance' => $balance]);
            }
            details::where('id', $id)->delete();
            if ($c1->payment_method == 'cash') {
                $ce = accounts::where('id', $c1['account_id'])->first();
                SystemOperation::create([
                    'user_id' => auth()->id(),
                    'operation_type' => 'حذف',
                    'details' => 'حذف تفاصيل - العنصر: ' . items::find($c1['item_id'])->item . ', الفرع: ' . Branch::find($c1['branch_id'])->branch . ', الحساب: ' . $ce->account . ', المبلغ: ' . $c1['total'],
                    'status' => 'successful',
                ]);
            } else {
                $ce = Suppliers::where('id', $c1['account_id'])->first();
                SystemOperation::create([
                    'user_id' => auth()->id(),
                    'operation_type' => 'حذف',
                    'details' => 'حذف تفاصيل - العنصر: ' . items::find($c1['item_id'])->item . ', الفرع: ' . Branch::find($c1['branch_id'])->branch . ', الحساب: ' . $ce->supplier . ', المبلغ: ' . $c1['total'],
                    'status' => 'successful',
                ]);
            }

            return redirect()->back()->with('success', 'تم حذف التفاصيل بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف التفاصيل.');
        }
    }

}