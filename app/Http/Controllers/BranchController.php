<?php
namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\SystemOperation;
use Illuminate\Http\Request;
use Exception;

class BranchController extends Controller
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
            $data = $request->branch_name;
            $branch = Branch::create([
                'branch' => $data,
            ]);

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'إضافة',
                'details' => 'إضافة فرع جديد: ' . $branch->branch,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم إنشاء الفرع بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الفرع.');
        }
    }

    public function show(Branch $branch)
    {
        // تنفيذ الكود هنا
    }

    public function edit(Branch $branch)
    {
        // تنفيذ الكود هنا
    }

    public function update(Request $request)
    {
        try {
            $b1['branch'] = $request->newName;
            Branch::where('id', $request->id)->update($b1);

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'تعديل',
                'details' => 'تعديل فرع: ' . $request->newName,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم تحديث الفرع بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الفرع.');
        }
    }

    public function destroy($id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $branchName = $branch->branch;
            $branch->delete();

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'حذف',
                'details' => 'حذف فرع: ' . $branchName,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم حذف الفرع بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الفرع.');
        }
    }
}