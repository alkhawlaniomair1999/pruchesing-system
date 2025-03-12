<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_type', // نوع العملية (إضافة، تعديل، حذف)
        'details',        // تفاصيل العملية
        'user_id',        // معرف المستخدم الذي قام بالعملية
    ];

    // العلاقة مع المستخدم (اختياري)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
