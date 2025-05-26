<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Order::with('user')->latest();

        // Apply filters if provided
        if ($this->request->has('status') && !empty($this->request->status)) {
            $query->where('status', $this->request->status);
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Email',
            'Total Amount',
            'Payment Method',
            'Status',
            'Order Date'
        ];
    }

    /**
     * @param mixed $order
     * @return array
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->user->first_name . ' ' . $order->user->last_name,
            $order->user->email,
            'JOD ' . number_format($order->total_amount, 2),
            $order->payment_method,
            $order->status,
            $order->created_at->format('Y-m-d H:i'),
        ];
    }
}