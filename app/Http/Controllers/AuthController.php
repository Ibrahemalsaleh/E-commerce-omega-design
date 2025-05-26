<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * عرض نموذج تسجيل الدخول
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    /**
     * معالجة تسجيل الدخول
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            
            // توجيه الأدمن إلى لوحة التحكم
            if (Auth::user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard')
                    ->with('success', 'تم تسجيل الدخول بنجاح! مرحباً بعودتك ' . Auth::user()->first_name . '!');
            }
            
            return redirect()->intended('/')
                ->with('success', 'تم تسجيل الدخول بنجاح! مرحباً بعودتك ' . Auth::user()->first_name . '!');
        }
        
        return back()->withErrors([
            'login_error' => 'هنالك خطأ في الايميل او الباسورد يرجى اعاده كتابته بطريقه صحيحه',
        ]);
    }
    
    /**
     * عرض نموذج التسجيل
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    
    /**
     * معالجة تسجيل مستخدم جديد
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);
        
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $request->phone_number,
            'address' => $request->address
        ]);
        
        Auth::login($user);
        
        return redirect('/')->with('success', 'تم إنشاء حسابك بنجاح! مرحباً بك ' . $user->first_name . '!');
    }
    
    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
    
    /**
     * عرض نموذج نسيت كلمة المرور
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
    
    /**
     * إرسال رابط إعادة تعيين كلمة المرور
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        // إنشاء رمز إعادة تعيين كلمة المرور
        $token = Str::random(64);
        
        PasswordReset::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours(24)
        ]);
        
        // إرسال البريد الإلكتروني
        Mail::to($user->email)->send(new ResetPasswordMail($token));
        
        return back()->with('status', 'سنرسل لك رابط إعادة تعيين كلمة المرور عبر البريد الإلكتروني!');
    }
    
    /**
     * عرض نموذج إعادة تعيين كلمة المرور
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }
    
    /**
     * معالجة إعادة تعيين كلمة المرور
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $passwordReset = PasswordReset::where('token', $request->token)
            ->whereHas('user', function($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->where('expires_at', '>', Carbon::now())
            ->first();
            
        if (!$passwordReset) {
            return back()->withErrors(['email' => 'رمز إعادة التعيين غير صالح أو منتهي الصلاحية']);
        }
        
        $user = $passwordReset->user;
        $user->password = Hash::make($request->password);
        $user->save();
        
        // حذف الرمز بعد الاستخدام
        $passwordReset->delete();
        
        // تسجيل الدخول تلقائياً بعد إعادة تعيين كلمة المرور
        Auth::login($user);
        
        return redirect('/')->with('status', 'تم إعادة تعيين كلمة المرور بنجاح!');
    }
}