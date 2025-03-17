<?php
namespace App\Http\Controllers;

use App\Models\cashers;
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

            cashers::create($cash);
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
            cashers::where('id', $request->id)->update($cash);
            return redirect()->back()->with('success', 'تم تحديث الكاشير بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الكاشير.');
        }
    }

    public function destroy($id)
    {
        try {
            cashers::where('id', $id)->delete();
            return redirect()->back()->with('success', 'تم حذف الكاشير بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الكاشير.');
        }
    }
}