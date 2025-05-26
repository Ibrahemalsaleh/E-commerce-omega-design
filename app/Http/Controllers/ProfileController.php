<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * عرض صفحة الملف الشخصي
     */
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user()
        ]);
    }
    
    /**
     * عرض نموذج تعديل الملف الشخصي
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }
    
    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request)
{
    $user = Auth::user();
    
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'phone_number' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ]);
    
    // تحديث البيانات الأساسية
    $user->first_name = $validated['first_name'];
    $user->last_name = $validated['last_name'];
    $user->email = $validated['email'];
    $user->phone_number = $request->phone_number;
    $user->address = $request->address;
    
    $user->save();
    
    return redirect()->route('profile.show')->with('status', 'تم تحديث الملف الشخصي بنجاح!');
}

/**
 * تحديث كلمة مرور المستخدم
 */
public function updatePassword(Request $request)
{
    $user = Auth::user();
    
    $validated = $request->validate([
        'current_password' => 'required',
        'password' => 'required|string|min:8|confirmed',
    ]);
    
    // التحقق من كلمة المرور الحالية
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
    }
    
    // تحديث كلمة المرور
    $user->password = Hash::make($validated['password']);
    $user->save();
    
    return redirect()->route('profile.show')->with('status', 'تم تحديث كلمة المرور بنجاح!');
}









}