<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    
        protected $fillable = [
            'user_id',
            'total_amount',
            'status',
            'shipping_address',
            'payment_method',
            'first_name',
            'last_name',
            'email',
            'phone',
            'subtotal',       // أضف هذه
            'shipping_cost',  // أضف هذه
            'tax',            // أضف هذه
        ];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * الحصول على اسم الحالة باللغة العربية
     */
    public function getStatusArabicAttribute()
    {
        return [
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي'
        ][$this->status] ?? $this->status;
    }
    public function getPaymentMethodArabicAttribute()
{
    return [
        'credit_card' => 'بطاقة ائتمان',
        'cash_on_delivery' => 'الدفع عند الاستلام'
    ][$this->payment_method] ?? $this->payment_method;
}
}