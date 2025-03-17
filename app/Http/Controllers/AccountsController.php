<?php
namespace App\Http\Controllers;

use App\Models\accounts;
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
            accounts::create([
                'account' => $data,
                'debt' => 0,
                'credit' => 0,
                'balance' => 0,
                'branch_id' => $d_branch,
                'type' => $d_type,
            ]);
            return redirect()->back()->with('success', 'تم إنشاء الحساب بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الحساب.');
        }
    }

    public function show(accounts $accounts)
    {
        // تنفيذ الكود هنا
    }

    public function edit(accounts $accounts)
    {
        // تنفيذ الكود هنا
    }

    public function update(Request $request)
    {
        try {
            $b1['account'] = $request->newName;
            $b1['type'] = $request->type;

            accounts::where('id', $request->id)->update($b1);
            return redirect()->back()->with('success', 'تم تحديث الحساب بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الحساب.');
        }
    }

    public function destroy($id)
    {
        try {
            accounts::where('id', $id)->delete();
            return redirect()->back()->with('success', 'تم حذف الحساب بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف ��لحساب.');
        }
    }
}