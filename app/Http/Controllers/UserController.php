<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('user', compact('users'));
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
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255', 'unique:users,name'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ], [
        // رسائل الخطأ المخصصة
        'name.required' => 'اسم المستخدم مطلوب.',
        'name.unique' => 'اسم المستخدم موجود بالفعل.',
        'email.required' => 'البريد الإلكتروني مطلوب.',
        'email.unique' => 'البريد الإلكتروني موجود بالفعل.',
        'password.required' => 'كلمة المرور مطلوبة.',
        'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل.',
        'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
    ]);

    // إضافة المستخدم إذا تم التحقق بنجاح
    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    return redirect()->back()->with('success', 'تمت إضافة المستخدم بنجاح.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $id],
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'اسم المستخدم مطلوب.',
            'name.unique' => 'اسم المستخدم موجود بالفعل.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'البريد الإلكتروني موجود بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);
        
    
        $user = User::findOrFail($id);
    
        $user->name = $validated['name'];
        $user->email = $validated['email'];
    
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
    
        $user->save();
    
        
    
        return redirect()->back()->with('status', 'تم تحديث بيانات المستخدم بنجاح.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // التحقق من عدد المستخدمين
        $userCount = User::count();
    
        if ($userCount <= 1) {
            return redirect()->back()->with('error', 'لا يمكن حذف المستخدم الأخير في النظام.');
        }
    
        // البحث عن المستخدم وحذفه
        $user = User::findOrFail($id);
    
        // حذف المستخدم
        $user->delete();
    
        return redirect()->back()->with('success', 'تم حذف المستخدم بنجاح.');
    }
    
}
