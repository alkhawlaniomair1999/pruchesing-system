<?php
namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Branch;
use App\Models\SystemOperation;
use Illuminate\Http\Request;
use Exception;

class AccountsController extends Controller
{
    public function index()
    {
        // تنفيذ الكود هنا
    }

    public function create()
    {
        // تنفيذ الكود هنا
    }

    public function store(Request $request)
    {
        try {
            $data = $request->account_name;
            $d_branch = $request->branch;
            $d_type = $request->type;
            $account = Accounts::create([
                'account' => $data,
                'debt' => 0,
                'credit' => 0,
                'balance' => 0,
                'branch_id' => $d_branch,
                'type' => $d_type,
            ]);
            $br = Branch::where('id',$d_branch)->first();
            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'إضافة',
                'details' => 'إضافة حساب جديد: ' . $account->account . ', الفرع: ' . $br->branch ,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم إنشاء الحساب بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الحساب.');
        }
    }

    public function show(Accounts $accounts)
    {
        // تنفيذ الكود هنا
    }

    public function edit(Accounts $accounts)
    {
        // تنفيذ الكود هنا
    }

    public function update(Request $request)
    {
        try {
            $b1['account'] = $request->newName;
            $b1['type'] = $request->type;

            Accounts::where('id', $request->id)->update($b1);

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'تعديل',
                'details' => 'تعديل حساب: ' . $request->newName . ', النوع الجديد: ' . $request->type,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم تحديث الحساب بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الحساب.');
        }
    }

    public function destroy($id)
    {
        try {
            $account = Accounts::findOrFail($id);
            $accountName = $account->account;
            $account->delete();

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'حذف',
                'details' => 'حذف حساب: ' . $accountName,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم حذف الحساب بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الحساب.');
        }
    }
}