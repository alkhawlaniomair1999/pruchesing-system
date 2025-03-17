<?php
namespace App\Http\Controllers;

use App\Models\sys_procs;
use Illuminate\Http\Request;
use Exception;

class SysProcsController extends Controller
{
    public function index()
    {
        try {
            $sysProcs = sys_procs::all();
            return view('sys_procs.index', compact('sysProcs'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب البيانات.');
        }
    }

    public function create()
    {
        try {
            return view('sys_procs.create');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء عرض نموذج الإنشاء.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            sys_procs::create($validatedData);

            return redirect()->route('sys_procs.index')->with('success', 'تم إنشاء العملية بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء العملية.');
        }
    }

    public function show(sys_procs $sys_procs)
    {
        try {
            return view('sys_procs.show', compact('sys_procs'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء عرض البيانات.');
        }
    }

    public function edit(sys_procs $sys_procs)
    {
        try {
            return view('sys_procs.edit', compact('sys_procs'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء عرض نموذج التعديل.');
        }
    }

    public function update(Request $request, sys_procs $sys_procs)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $sys_procs->update($validatedData);

            return redirect()->route('sys_procs.index')->with('success', 'تم تحديث العملية بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث العملية.');
        }
    }

    public function destroy(sys_procs $sys_procs)
    {
        try {
            $sys_procs->delete();
            return redirect()->route('sys_procs.index')->with('success', 'تم حذف العملية بنجاح!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف العملية.');
        }
    }
}