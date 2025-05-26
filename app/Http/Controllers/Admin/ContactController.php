<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    /**
     * عرض قائمة الرسائل
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(10);

        return view('admin.contacts.index', compact('messages'));
    }

    /**
     * عرض رسالة محددة
     *
     * @param  \App\Models\ContactMessage  $contactMessage
     * @return \Illuminate\View\View
     */
    public function show(ContactMessage $contactMessage)
    {
        // Change the message status to "read" if it is unread
        if ($contactMessage->status === 'unread') {
            $contactMessage->update(['status' => 'read']);
        }

        return view('admin.contacts.show', compact('contactMessage'));
    }

    /**
     * تحديث حالة الرسالة
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactMessage  $contactMessage
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'status' => 'required|in:unread,read,replied',
        ]);

        $contactMessage->update(['status' => $request->status]);

        return redirect()->route('admin.contacts.show', $contactMessage)
            ->with('success', 'تم تحديث حالة الرسالة بنجاح');
    }

    /**
     * حذف الرسالة
     *
     * @param  \App\Models\ContactMessage  $contactMessage
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'تم حذف الرسالة بنجاح');
    }
}