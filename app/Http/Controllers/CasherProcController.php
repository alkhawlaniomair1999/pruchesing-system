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

    public function create()
    {
        // تنفيذ الكود هنا
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

    public function show($casher_proc)
    {
        // تنفيذ الكود هنا
    }

    public function edit($casher_proc)
    {
        // تنفيذ الكود هنا
    }

    public function update(Request $request)
    {
        try {
            // جلب السجل المطلوب من casher_procs مع التحقق من وجوده
            $cp = casher_procs::find($request->id);
    
            if (!$cp) {
                return response()->json(['error' => 'Casher process not found'], 404);
            }
    
            // جلب السجل المطلوب من cashers مع التحقق من وجوده
            $c = cashers::find($cp->casher_id);
    
            if (!$c) {
                return response()->json(['error' => 'Casher not found'], 404);
            }
    
            // إعداد البيانات للتحديث
            $cp->casher_id = $request->casher_id;
            $cp->date = $request->date;
            $cp->total = $request->total;
            $cp->bank = $request->bank;
            $cp->cash = $request->cash;
            $cp->out = $request->out;
            $cp->plus = $request->total - ($request->out + $request->cash + $request->bank);
    
            // حفظ التغييرات في قاعدة البيانات
            $cp->save();
    
            // تحديث الحسابات بناءً على casher_id
            $this->updateAccounts($cp, $request, $c);
    
            // تسجيل العملية
            $br = Branch::where('id', $c->branch_id)->first();
            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'تعديل',
                'details' => 'تعديل عملية كاشير - الكاشير: ' . $c->casher . ', الفرع: ' . $br->branch . ', المبلغ النقدي: ' . $request->cash . ', المبلغ البنكي: ' . $request->bank,
                'status' => 'successful',
            ]);
    
            return redirect()->back()->with('success', 'تم تحديث العملية بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث العملية.');
        }
    }
    
    private function updateAccounts($cp, $request, $c)
    {
        $cacher = cashers::where('id', $request->casher_id)->first();
        $sameBranch = $c->branch_id == $cacher->branch_id;
    
        $this->updateAccountBalances($c->branch_id, -$cp->cash, -$cp->bank);
    
        if ($sameBranch) {
            $this->updateAccountBalances($cacher->branch_id, $request->cash, $request->bank);
        } else {
            $this->updateAccountBalances($cacher->branch_id, $request->cash, $request->bank);
        }
    }
    
    private function updateAccountBalances($branchId, $cashChange, $bankChange)
    {
        $box = accounts::where('branch_id', $branchId)->where('type', 'box')->first();
        $box->debt += $cashChange;
        $box->balance += $cashChange;
        $box->save();
    
        $bank = accounts::where('branch_id', $branchId)->where('type', 'bank')->first();
        $bank->debt += $bankChange;
        $bank->balance += $bankChange;
        $bank->save();
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