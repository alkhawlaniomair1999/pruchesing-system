<?php
namespace App\Http\Controllers;

use App\Models\casher_procs;
use App\Models\cashers;
use App\Models\Branch;
use App\Models\accounts;
use App\Models\SystemOperation;
use Illuminate\Http\Request;
use Exception;

class CasherProcController extends Controller
{
    public function index()
    {
        try {
            $casher_proc = casher_procs::all();
            $casher = cashers::all();
            $branch = Branch::all();
            return view('casherproc', ['casher_proc' => $casher_proc, 'casher' => $casher, 'branch' => $branch]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب البيانات.');
        }
    }

    public function store(Request $request)
    {
        try {
            $data['casher_id'] = $request->casher;
            $data['date'] = $request->date;
            $data['total'] = $request->total;
            $data['bank'] = $request->bank;
            $data['cash'] = $request->cash;
            $data['out'] = $request->out;
            $data['plus'] = $request->total - ($request->out + $request->cash + $request->bank);
            casher_procs::create($data);

            $cacher = cashers::where('id', $request->casher)->first();
            $box = accounts::where('branch_id', $cacher->branch_id)->where('type', 'box')->first();
            $box1 = $box->debt + $request->cash;
            $balance = $box->balance + $request->cash;
            accounts::where('id', $box->id)->update(['debt' => $box1, 'balance' => $balance]);

            $bank = accounts::where('branch_id', $cacher->branch_id)->where('type', 'bank')->first();
            $bank1 = $bank->debt + $request->bank;
            $balance = $bank->balance + $request->bank;
            accounts::where('id', $bank->id)->update(['debt' => $bank1, 'balance' => $balance]);
            $br = Branch::where('id',$cacher->branch_id)->first();
            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'إضافة',
                'details' => 'إضافة عملية كاشير - الكاشير: ' . $cacher->casher . ', الفرع: ' . $br->branch . ', المبلغ النقدي: ' . $request->cash . ', المبلغ البنكي: ' . $request->bank,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم إنشاء العملية بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء العملية.');
        }
    }

    public function update(Request $request)
    {
        try {
            // جلب العملية القديمة
            $cp = casher_procs::where('id', $request->id)->first();
            if (!$cp) {
                return redirect()->back()->with('error', 'العملية غير موجودة.');
            }
    
            // جلب الكاشير والفرع القديمين
            $old_casher = cashers::where('id', $cp->casher_id)->first();
            $old_branch = Branch::where('id', $old_casher->branch_id)->first();
    
            // جلب الحسابات القديمة
            $old_box = accounts::where('branch_id', $old_branch->id)->where('type', 'box')->first();
            $old_bank = accounts::where('branch_id', $old_branch->id)->where('type', 'bank')->first();
       
            // 1. إرجاع المبالغ القديمة من الحسابات القديمة
            if ($old_box) {
                $old_box->debt -= $cp->cash;
                $old_box->balance -= $cp->cash;
                $old_box->save();
            }
            if ($old_bank) {
                $old_bank->debt -= $cp->bank;
                $old_bank->balance -= $cp->bank;
                $old_bank->save();
            }
              // استرجاع الكاشير والفرع الجديدين
              $new_casher = cashers::where('id', $request->casher_id)->first();
              $new_branch = Branch::where('id', $new_casher->branch_id)->first();
      
              // جلب الحسابات الجديدة
              $new_box = accounts::where('branch_id', $new_branch->id)->where('type', 'box')->first();
              $new_bank = accounts::where('branch_id', $new_branch->id)->where('type', 'bank')->first();
   
    
            // 2. إضافة المبالغ الجديدة إلى الحسابات الجديدة
            if ($new_box) {
                $new_box->debt += $request->cash;
                $new_box->balance += $request->cash;
                $new_box->save();
            }
            if ($new_bank) {
                $new_bank->debt += $request->bank;
                $new_bank->balance += $request->bank;
                $new_bank->save();
            }
    
            // 3. تحديث بيانات العملية
            $cp->casher_id = $request->casher_id;
            $cp->date = $request->date;
            $cp->total = $request->total;
            $cp->bank = $request->bank;
            $cp->cash = $request->cash;
            $cp->out = $request->out;
            $cp->plus = $request->total - ($request->out + $request->cash + $request->bank);
            $cp->save();
    
            // 4. تسجيل العملية في SystemOperation
            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'تعديل',
                'details' => 'تعديل عملية كاشير - الكاشير القديم: ' . $old_casher->casher . ', الفرع القديم: ' . $old_branch->branch .
                    ', الكاشير الجديد: ' . $new_casher->casher . ', الفرع الجديد: ' . $new_branch->branch .
                    ', المبلغ النقدي الجديد: ' . $request->cash . ', المبلغ البنكي الجديد: ' . $request->bank,
                'status' => 'successful',
            ]);
    
            return redirect()->back()->with('success', 'تم تعديل العملية بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل العملية.');
        }
    }

    public function destroy($id)
    {
        try {
            $cp = casher_procs::where('id', $id)->first();
            $c = cashers::where('id', $cp->casher_id)->first();
            $b = Branch::where('id', $c->branch_id)->first();
            $box = accounts::where('branch_id', $b->id)->where('type', 'box')->first();
            $box1 = $box->debt - $cp->cash;
            $balance = $box->balance - $cp->cash;
            accounts::where('id', $box->id)->update(['debt' => $box1, 'balance' => $balance]);

            $bank = accounts::where('branch_id', $b->id)->where('type', 'bank')->first();
            $bank1 = $bank->debt - $cp->bank;
            $balance = $bank->balance - $cp->bank;
            accounts::where('id', $bank->id)->update(['debt' => $bank1, 'balance' => $balance]);

            casher_procs::where('id', $id)->delete();
            $br = Branch::where('id',$c->branch_id)->first();
            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'حذف',
                'details' => 'حذف عملية كاشير - الكاشير: ' . $c->casher . ', الفرع: ' . $br->branch . ', المبلغ النقدي: ' . $cp->cash . ', المبلغ البنكي: ' . $cp->bank,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم حذف العملية بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف العملية.');
        }
    }
}