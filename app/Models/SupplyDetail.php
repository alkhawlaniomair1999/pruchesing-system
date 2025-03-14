<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',    // معرف المورد
        'amount',         // قيمة التوريد
        'payment_type',   // نوع الدفع (نقداً أو آجلاً)
        'details',        // تفاصيل إضافية
        'account_name',   // اسم الحساب إذا كان الدفع نقداً
    ];

    // العلاقة مع المورد
    // public function supplier()
    // {
    //     return $this->belongsTo(Suppliers::class, 'id');
    // }
    public function supplier()
{
    return $this->belongsTo(Suppliers::class);
}

}
