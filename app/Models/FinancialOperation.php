<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'related_id',     // المعرف المرتبط (الحساب أو المورد)
        'related_type',   // نوع المرتبط (Account أو Supplier)
        'operation_type', // نوع العملية (إيداع، سحب، توريد، إلخ)
        'debit',          // المدين
        'credit',         // الدائن
        'balance',        // الرصيد الجديد بعد العملية
        'details',        // تفاصيل العملية
        'user_id',        // المستخدم الذي نفذ العملية
    ];

    // العلاقة البوليمورفية
    public function related()
    {
        return $this->morphTo();
    }
}
