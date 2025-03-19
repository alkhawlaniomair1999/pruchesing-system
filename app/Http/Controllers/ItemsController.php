<?php
namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\items;
use App\Models\SystemOperation;
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
            $item = items::create([
                'item' => $data,
            ]);

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'إضافة',
                'details' => 'إضافة عنصر جديد: ' . $item->item,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم إنشاء العنصر بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء العنصر.');
        }
    }

    public function show(items $items)
    {
        // تنفيذ الكود هنا
    }

    public function edit(items $items)
    {
        // تنفيذ الكود هنا
    }

    public function update(Request $request)
    {
        try {
            $b1['item'] = $request->newName;
            items::where('id', $request->id)->update($b1);

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'تعديل',
                'details' => 'تعديل عنصر: ' . $request->newName,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم تحديث العنصر بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث العنصر.');
        }
    }

    public function destroy($id)
    {
        try {
            $item = items::findOrFail($id);
            $itemName = $item->item;
            $item->delete();

            SystemOperation::create([
                'user_id' => auth()->id(),
                'operation_type' => 'حذف',
                'details' => 'حذف عنصر: ' . $itemName,
                'status' => 'successful',
            ]);

            return redirect()->back()->with('success', 'تم حذف العنصر بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف العنصر.');
        }
    }
}