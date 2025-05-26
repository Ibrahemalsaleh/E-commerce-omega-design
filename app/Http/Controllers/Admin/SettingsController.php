<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * عرض صفحة الإعدادات
     */
    public function index()
    {
        // استرجاع الإعدادات من قاعدة البيانات أو ملف التكوين
        $settings = [
            'site_name' => config('app.name'),
            'site_email' => config('mail.from.address'),
            'currency' => 'ر.س',  // يمكن استبدالها بقيمة من قاعدة البيانات
            'tax_percentage' => '15',  // يمكن استبدالها بقيمة من قاعدة البيانات
            // إضافة المزيد من الإعدادات حسب الحاجة
        ];
        
        return view('admin.settings.index', compact('settings'));
    }
    
    /**
     * تحديث الإعدادات
     */
    public function update(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'currency' => 'required|string|max:10',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'shipping_cost' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'required|numeric|min:0',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'footer_text' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
        ]);
        
        // معالجة تحميل الصورة إذا تم توفيرها
        if ($request->hasFile('site_logo')) {
            // حذف الصورة القديمة إذا وجدت
            if (!empty($request->old_logo)) {
                Storage::disk('public')->delete($request->old_logo);
            }
            
            // تحميل الصورة الجديدة
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            $validated['site_logo'] = $logoPath;
        }
        
        // حفظ الإعدادات في قاعدة البيانات أو ملف التكوين
        // يمكنك استخدام نموذج Setting إذا كان لديك جدول للإعدادات
        // أو استخدام حزمة مثل Laravel Settings
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
