<?php
namespace App\Http\Controllers;

use App\Models\Cashers;
use App\Models\Branch;
use App\Models\SystemOperation;
use Illuminate\Http\Request;
use Exception;

class CasherController extends Controller
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
            $cash['casher'] = $request->casher;
            $cash['branch_id'] = $request->branch;
            $br = Branch::where('id',$request->branch)->first();
            $casher = Cashers::create($cash);

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'إضافة',
                'details' => 'إضافة كاشير جديد: ' . $casher->casher . ', الفرع: ' . $br->branch,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم إنشاء الكاشير بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الكاشير.');
        }
    }

    public function show($casher)
    {
        // تنفيذ الكود هنا
    }

    public function edit($casher)
    {
        // تنفيذ الكود هنا
    }

    public function update(Request $request)
    {
        try {
            $cash['casher'] = $request->newName;
            $cash['branch_id'] = $request->branch;
            $br = Branch::where('id',$request->branch)->first();
            Cashers::where('id', $request->id)->update($cash);

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'تعديل',
                'details' => 'تعديل كاشير: ' . $request->newName . ', الفرع الجديد: ' . $br->branch,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم تحديث الكاشير بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الكاشير.');
        }
    }

    public function destroy($id)
    {
        try {
            $casher = Cashers::findOrFail($id);
            $casherName = $casher->casher;
            $casher->delete();

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'حذف',
                'details' => 'حذف كاشير: ' . $casherName,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم حذف الكاشير بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الكاشير.');
        }
    }
}