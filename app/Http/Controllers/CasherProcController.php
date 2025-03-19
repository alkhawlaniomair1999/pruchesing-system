<?php
namespace App\Http\Controllers;

use App\Models\CasherProcs;
use App\Models\Cashers;
use App\Models\Branch;
use App\Models\Accounts;
use App\Models\SystemOperation;
use Illuminate\Http\Request;
use Exception;

class CasherProcController extends Controller
{
    public function index()
    {
        try {
            $casher_proc = CasherProcs::all();
            $casher = Cashers::all();
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
            CasherProcs::create($data);

            $cacher = Cashers::where('id', $request->casher)->first();
            $box = Accounts::where('branch_id', $cacher->branch_id)->where('type', 'box')->first();
            $box1 = $box->debt + $request->cash;
            $balance = $box->balance + $request->cash;
            Accounts::where('id', $box->id)->update(['debt' => $box1, 'balance' => $balance]);

            $bank = Accounts::where('branch_id', $cacher->branch_id)->where('type', 'bank')->first();
            $bank1 = $bank->debt + $request->bank;
            $balance = $bank->balance + $request->bank;
            Accounts::where('id', $bank->id)->update(['debt' => $bank1, 'balance' => $balance]);
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
            $cp = CasherProcs::where('id', $request->id)->first();
            $c = Cashers::where('id', $cp->casher_id)->first();
            $data['casher_id'] = $request->casher_id;
            $data['date'] = $request->date;
            $data['total'] = $request->total;
            $data['bank'] = $request->bank;
            $data['cash'] = $request->cash;
            $data['out'] = $request->out;
            $data['plus'] = $request->total - ($request->out + $request->cash + $request->bank);
            CasherProcs::where('id', $request->id)->update($data);

            if ($cp->casher_id == $request->casher_id) {
                $cacher = Cashers::where('id', $request->casher_id)->first();
                $box = Accounts::where('branch_id', $cacher->branch_id)->where('type', 'box')->first();
                $box1 = $box->debt - $cp->cash + $request->cash;
                $balance = $box->balance - $cp->cash + $request->cash;
                Accounts::where('id', $box->id)->update(['debt' => $box1, 'balance' => $balance]);

                $bank = Accounts::where('branch_id', $cacher->branch_id)->where('type', 'bank')->first();
                $bank1 = $bank->debt - $cp->bank + $request->bank;
                $balance = $bank->balance - $cp->bank + $request->bank;
                Accounts::where('id', $bank->id)->update(['debt' => $bank1, 'balance' => $balance]);
            } else {
                $c2 = Cashers::where('id', $request->casher_id)->first();
                if ($c->branch_id == $c2->branch_id) {
                    $cacher = Cashers::where('id', $request->casher_id)->first();
                    $box = Accounts::where('branch_id', $cacher->branch_id)->where('type', 'box')->first();
                    $box1 = $box->debt - $cp->cash + $request->cash;
                    $balance = $box->balance - $cp->cash + $request->cash;
                    Accounts::where('id', $box->id)->update(['debt' => $box1, 'balance' => $balance]);

                    $bank = Accounts::where('branch_id', $cacher->branch_id)->where('type', 'bank')->first();
                    $bank1 = $bank->debt - $cp->bank + $request->bank;
                    $balance = $bank->balance - $cp->bank + $request->bank;
                    Accounts::where('id', $bank->id)->update(['debt' => $bank1, 'balance' => $balance]);
                } else {
                    $box = Accounts::where('branch_id', $c->branch_id)->where('type', 'box')->first();
                    $box1 = $box->debt - $cp->cash;
                    $balance = $box->balance - $request->cash;
                    Accounts::where('id', $box->id)->update(['debt' => $box1, 'balance' => $balance]);

                    $bank = Accounts::where('branch_id', $c->branch_id)->where('type', 'bank')->first();
                    $bank1 = $bank->debt - $cp->bank;
                    $balance = $bank->balance - $cp->bank;
                    Accounts::where('id', $bank->id)->update(['debt' => $bank1, 'balance' => $balance]);

                    $cacher = Cashers::where('id', $request->casher_id)->first();
                    $box = Accounts::where('branch_id', $cacher->branch_id)->where('type', 'box')->first();
                    $box1 = $box->debt + $request->cash;
                    $balance = $box->balance + $request->cash;
                    Accounts::where('id', $box->id)->update(['debt' => $box1, 'balance' => $balance]);

                    $bank = Accounts::where('branch_id', $cacher->branch_id)->where('type', 'bank')->first();
                    $bank1 = $bank->debt + $request->bank;
                    $balance = $bank->balance + $request->bank;
                    Accounts::where('id', $bank->id)->update(['debt' => $bank1, 'balance' => $balance]);
                }
            }
            $br = Branch::where('id',$c->branch_id)->first();
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

    public function destroy($id)
    {
        try {
            $cp = CasherProcs::where('id', $id)->first();
            $c = Cashers::where('id', $cp->casher_id)->first();
            $b = Branch::where('id', $c->branch_id)->first();
            $box = Accounts::where('branch_id', $b->id)->where('type', 'box')->first();
            $box1 = $box->debt - $cp->cash;
            $balance = $box->balance - $cp->cash;
            Accounts::where('id', $box->id)->update(['debt' => $box1, 'balance' => $balance]);

            $bank = Accounts::where('branch_id', $b->id)->where('type', 'bank')->first();
            $bank1 = $bank->debt - $cp->bank;
            $balance = $bank->balance - $cp->bank;
            Accounts::where('id', $bank->id)->update(['debt' => $bank1, 'balance' => $balance]);

            CasherProcs::where('id', $id)->delete();
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