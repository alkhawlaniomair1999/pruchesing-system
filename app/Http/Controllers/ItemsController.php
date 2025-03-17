<?php
namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\items;
use Illuminate\Http\Request;
use Exception;

class ItemsController extends Controller
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
            $data = $request->category_name;
            items::create([
                'item' => $data,
            ]);
            return redirect()->back()->with('success', 'تم إنشاء العنصر بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء العنصر.');
        }
    }

    public function show(items $accounts)
    {
        // تنفيذ الكود هنا
    }

    public function edit(items $accounts)
    {
        // تنفيذ الكود هنا
    }

    public function update(Request $request)
    {
        try {
            $b1['item'] = $request->newName;
            items::where('id', $request->id)->update($b1);
            return redirect()->back()->with('success', 'تم تحديث العنصر بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث العنصر.');
        }
    }

    public function destroy($id)
    {
        try {
            items::where('id', $id)->delete();
            return redirect()->back()->with('success', 'تم حذف العنصر بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف العنصر.');
        }
    }
}