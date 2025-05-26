<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order status updated successfully');
    }
    
    public function print(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.print', compact('order'));
    }

    public function addNote(Request $request, Order $order)
    {
        $validated = $request->validate([
            'note' => 'required|string',
        ]);
        
        // هنا يمكن إضافة الكود لحفظ الملاحظة
        // يمكن إنشاء جدول للملاحظات أو إضافة حقل ملاحظات في جدول الطلبات
        
        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Note added successfully');
    }

    public function export()
    {
        // Export order data to CSV/Excel
        $orders = Order::with(['user', 'items.product'])->get();

        // Implement the export process (you can use a library like Laravel Excel)

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order data exported successfully');
    }
    
    public function edit(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * تصدير الطلبات بصيغة PDF
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $query = Order::with('user')->latest();

        // Apply filters if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        $pdf = PDF::loadView('admin.orders.pdf', compact('orders'));
        
        return $pdf->download('orders.pdf');
    }

    /**
     * تصدير طلب واحد بصيغة PDF
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function exportSinglePdf(Order $order)
    {
        $order->load(['user', 'items.product']);
        
        $pdf = PDF::loadView('admin.orders.single-pdf', compact('order'));
        
        return $pdf->download('order-' . $order->id . '.pdf');
    }

    /**
     * تصدير الطلبات بصيغة Excel
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new OrdersExport($request), 'orders.xlsx');
    }

    /**
     * تصدير الطلبات بصيغة Word
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportWord(Request $request)
    {
        $query = Order::with('user')->latest();

        // Apply filters if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        // Create new Word document
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Add title
        $section->addText('قائمة الطلبات', ['bold' => true, 'size' => 16]);
        $section->addTextBreak(1);
        
        // Add table
        $table = $section->addTable([
            'borderSize' => 1, 
            'borderColor' => '000000', 
            'cellMargin' => 80
        ]);
        
        // Add table header
        $table->addRow();
        $table->addCell(1000)->addText('رقم الطلب');
        $table->addCell(2500)->addText('اسم العميل');
        $table->addCell(2000)->addText('المبلغ الإجمالي');
        $table->addCell(1500)->addText('الحالة');
        $table->addCell(2000)->addText('تاريخ الطلب');
        
        // Add table data
        foreach ($orders as $order) {
            $table->addRow();
            $table->addCell(1000)->addText($order->id);
            $table->addCell(2500)->addText($order->user->first_name . ' ' . $order->user->last_name);
            $table->addCell(2000)->addText($order->total_amount);
            $table->addCell(1500)->addText($order->status);
            $table->addCell(2000)->addText($order->created_at->format('Y-m-d'));
        }
        
        // Save file
        $filename = 'orders.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path($filename));
        
        return response()->download(storage_path($filename))->deleteFileAfterSend(true);
    }
}