<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * عرض نموذج الاتصال
     */
    public function showForm()
    {
        return view('contact.show');
    }
    
    /**
     * إرسال رسالة اتصال
     */
    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);
        
        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread'
        ]);
        
        return redirect()->back()->with('success', 'تم إرسال رسالتك بنجاح. سنقوم بالرد عليك في أقرب وقت ممكن.');
    }
}