<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // تحديد الحقول القابلة للتعبئة
    protected $fillable = [
        'supplier', // معرف المورد
        'account',  // معرف الحساب
        'amount',   // مبلغ السداد
        'date',     // حقل التاريخ
        'details',  // حقل التفاصيل
    ];

    // العلاقة مع الموردين
    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier', 'id'); // العلاقة بجدول suppliers
    }

    // العلاقة مع الحسابات
    public function account()
    {
        return $this->belongsTo(Accounts::class, 'account', 'id'); // العلاقة بجدول accounts
    }
}
