<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContactMessagesExport;

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

    /**
     * تصدير رسائل الاتصال بصيغة PDF
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $query = ContactMessage::latest();

        // Apply filters if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $messages = $query->get();

        $pdf = PDF::loadView('admin.contacts.pdf', compact('messages'));
        
        return $pdf->download('contact-messages.pdf');
    }

    /**
     * تصدير رسالة واحدة بصيغة PDF
     *
     * @param  \App\Models\ContactMessage  $contactMessage
     * @return \Illuminate\Http\Response
     */
    public function exportSinglePdf(ContactMessage $contactMessage)
    {
        $pdf = PDF::loadView('admin.contacts.single-pdf', compact('contactMessage'));
        
        return $pdf->download('contact-message-' . $contactMessage->id . '.pdf');
    }

    /**
     * تصدير رسائل الاتصال بصيغة Excel
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new ContactMessagesExport($request), 'contact-messages.xlsx');
    }

    /**
     * تصدير رسائل الاتصال بصيغة Word
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportWord(Request $request)
    {
        $query = ContactMessage::latest();

        // Apply filters if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $messages = $query->get();

        // Create new Word document
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Add title
        $section->addText('رسائل الاتصال', ['bold' => true, 'size' => 16]);
        $section->addTextBreak(1);
        
        // Add table
        $table = $section->addTable([
            'borderSize' => 1, 
            'borderColor' => '000000', 
            'cellMargin' => 80
        ]);
        
        // Add table header
        $table->addRow();
        $table->addCell(1000)->addText('الرقم');
        $table->addCell(3000)->addText('الاسم');
        $table->addCell(3000)->addText('البريد الإلكتروني');
        $table->addCell(3000)->addText('الموضوع');
        $table->addCell(1500)->addText('الحالة');
        $table->addCell(2000)->addText('التاريخ');
        
        // Add table data
        foreach ($messages as $message) {
            $table->addRow();
            $table->addCell(1000)->addText($message->id);
            $table->addCell(3000)->addText($message->name);
            $table->addCell(3000)->addText($message->email);
            $table->addCell(3000)->addText($message->subject);
            $table->addCell(1500)->addText($message->status);
            $table->addCell(2000)->addText($message->created_at->format('Y-m-d'));
        }
        
        // Save file
        $filename = 'contact-messages.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path($filename));
        
        return response()->download(storage_path($filename))->deleteFileAfterSend(true);
    }
}