<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * عرض قائمة الشهادات
     */
    public function index()
    {
        $testimonials = Testimonial::orderBy('created_at', 'desc')->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * عرض نموذج إنشاء شهادة جديدة
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * تخزين شهادة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'text' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'is_active' => 'sometimes|boolean',
        ]);

        // جعل الشهادة نشطة افتراضياً إذا لم يتم تحديدها
        $validated['is_active'] = $request->has('is_active') ? true : true;

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'تم إضافة الشهادة بنجاح');
    }

    /**
     * عرض تفاصيل شهادة محددة
     */
    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    /**
     * عرض نموذج تعديل شهادة
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * تحديث شهادة محددة
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'text' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'is_active' => 'sometimes|boolean',
        ]);

        // جعل الشهادة نشطة افتراضياً إذا لم يتم تحديدها
        $validated['is_active'] = $request->has('is_active') ? true : true;

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'تم تحديث الشهادة بنجاح');
    }

    /**
     * حذف شهادة محددة
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'تم حذف الشهادة بنجاح');
    }
}