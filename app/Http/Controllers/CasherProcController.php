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
        // التحقق من وجود العملية
        $cp = casher_procs::find($request->id);
        if (!$cp) {
            return redirect()->back()->with('error', 'العملية غير موجودة.');
        }

        // التحقق من وجود الكاشير الأصلي
        $c = cashers::find($cp->casher_id);
        if (!$c) {
            return redirect()->back()->with('error', 'الكاشير الأصلي غير موجود.');
        }

        // تحديث بيانات العملية
        $data = [
            'casher_id' => $request->casher_id,
            'date' => $request->date,
            'total' => $request->total,
            'bank' => $request->bank,
            'cash' => $request->cash,
            'out' => $request->out,
            'plus' => $request->total - ($request->out + $request->cash + $request->bank),
        ];
        $cp->update($data);

        // التحقق من وجود الكاشير الجديد
        $c2 = cashers::find($request->casher_id);
        if (!$c2) {
            return redirect()->back()->with('error', 'الكاشير الجديد غير موجود.');
        }

        // تحديث الحسابات بناءً على حالة الكاشير
        if ($cp->casher_id == $request->casher_id) {
            $this->updateAccounts($c->branch_id, $cp, $request);
        } else {
            if ($c->branch_id == $c2->branch_id) {
                $this->updateAccounts($c2->branch_id, $cp, $request);
            } else {
                $this->transferAccounts($c->branch_id, $c2->branch_id, $cp, $request);
            }
        }

        // تسجيل العملية في النظام
        $br = Branch::find($c->branch_id);
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

private function updateAccounts($branchId, $cp, $request)
{
    $box = accounts::where('branch_id', $branchId)->where('type', 'box')->first();
    if ($box) {
        $box1 = $box->debt - $cp->cash + $request->cash;
        $balance = $box->balance - $cp->cash + $request->cash;
        $box->update(['debt' => $box1, 'balance' => $balance]);
    }

    $bank = accounts::where('branch_id', $branchId)->where('type', 'bank')->first();
    if ($bank) {
        $bank1 = $bank->debt - $cp->bank + $request->bank;
        $balance = $bank->balance - $cp->bank + $request->bank;
        $bank->update(['debt' => $bank1, 'balance' => $balance]);
    }
}

private function transferAccounts($oldBranchId, $newBranchId, $cp, $request)
{
    // تحديث الحسابات للفرع القديم
    $oldBox = accounts::where('branch_id', $oldBranchId)->where('type', 'box')->first();
    if ($oldBox) {
        $box1 = $oldBox->debt - $cp->cash;
        $balance = $oldBox->balance - $cp->cash;
        $oldBox->update(['debt' => $box1, 'balance' => $balance]);
    }

    $oldBank = accounts::where('branch_id', $oldBranchId)->where('type', 'bank')->first();
    if ($oldBank) {
        $bank1 = $oldBank->debt - $cp->bank;
        $balance = $oldBank->balance - $cp->bank;
        $oldBank->update(['debt' => $bank1, 'balance' => $balance]);
    }

    // تحديث الحسابات للفرع الجديد
    $newBox = accounts::where('branch_id', $newBranchId)->where('type', 'box')->first();
    if ($newBox) {
        $box1 = $newBox->debt + $request->cash;
        $balance = $newBox->balance + $request->cash;
        $newBox->update(['debt' => $box1, 'balance' => $balance]);
    }

    $newBank = accounts::where('branch_id', $newBranchId)->where('type', 'bank')->first();
    if ($newBank) {
        $bank1 = $newBank->debt + $request->bank;
        $balance = $newBank->balance + $request->bank;
        $newBank->update(['debt' => $bank1, 'balance' => $balance]);
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