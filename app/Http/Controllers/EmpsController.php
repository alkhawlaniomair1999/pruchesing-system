<?php

namespace App\Http\Controllers;

use App\Models\emps;
use App\Models\disses;
use Illuminate\Http\Request;

class EmpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emps = emps::all();
        $disses = disses::all();
        return view('emp', compact('emps', 'disses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name_emp' => 'required',
                'branch' => 'required',
                'country' => 'required',
                'salary' => 'required',
                'date_hirring' => 'required',
            ]);
    
            emps::create($request->all());
            $data2=emps::select()->orderby('id','DESC')->first();
            $data1['emp_id']=$data2->id;
            $data1['slf']=0;
            $data1['absence']=0;
            $data1['discount']=0;
            $data1['bank']=0;
            disses::create($data1);
    
            return redirect()->route('emps.index')->with('success', 'تم اضافة الموظف بنجاح');  
              } catch (\Throwable $th) {
                return redirect()->route('emps.index')->with('error', 'حدث خطأ أثناء إضافة الموظف');}
       
    }

    /**
     * Display the specified resource.
     */
    public function show(emps $emps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(emps $emps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name_emp' => 'required',
                'branch' => 'required',
                'country' => 'required',
                'salary' => 'required',
                'date_hirring' => 'required',
            ]);
    
            emps::where('id', $request->id)->update($request->except('_token', '_method'));
    
            return redirect()->route('emps.index')->with('success', 'تم تعديل الموظف بنجاح');  
              } catch (\Throwable $th) {
                return redirect()->route('emps.index')->with('error', 'حدث خطأ أثناء تعديل الموظف');}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            emps::find($id)->delete();
            return redirect()->route('emps.index')->with('success', 'تم حذف الموظف بنجاح');  
              } catch (\Throwable $th) {
                return redirect()->route('emps.index')->with('error', 'حدث خطأ أثناء حذف الموظف');}
    }
    public function dis(Request $request)
    {
        try {
            $request->validate([
                'slf' => 'required',
                'absence' => 'required',
                'discount' => 'required',
                'bank' => 'required',
            ]);
    
            disses::where('emp_id', $request->id)->update($request->except('_token', '_method'));
    
            return redirect()->route('emps.index')->with('success', 'تم تعديل الخصومات بنجاح');  
              } catch (\Throwable $th) {
                return redirect()->route('emps.index')->with('error', 'حدث خطأ أثناء تعديل الخصومات');}
    }
    public function print($id)
    {
        $emps = emps::find($id);
        $dis = disses::where('emp_id', $id)->first();
        return view('print.print_emp', compact('emps', 'dis'));
    }
    public function zeros()
    {
        $disses = disses::all();
        foreach ($disses as $dis) {
            $dis->slf = 0;
            $dis->absence = 0;
            $dis->discount = 0;
            $dis->save();
        }
        return redirect()->route('emps.index')->with('success', 'تم تصفير الخصومات بنجاح');  
    }
    public function print_report(){
        $emps = emps::all();
        $dis = disses::all();
        return view('print.print_report', compact('emps', 'dis'));
    }
}
