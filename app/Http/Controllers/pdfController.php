<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoices;
use App\Models\invoice_details;
use Barryvdh\Snappy\Facades\SnappyPdf;

class pdfController extends Controller
{
    public function generatePDF($id)
    {
        // جلب بيانات الفاتورة وتفاصيلها
        $invoice = invoices::findOrFail($id);
        $invoice_details = invoice_details::where('invoice_id', $id)->get();
        
        // تمرير البيانات إلى العرض
        $data = compact('invoice', 'invoice_details');

        // تحديد المسار لأداة wkhtmltopdf
        $binaryPath = env('WKHTMLTOPDF_BINARY', 'D:/wkhtmltopdf/bin/wkhtmltopdf.exe');
        SnappyPdf::setBinary($binaryPath);

        // إنشاء ملف PDF
        $pdf = SnappyPdf::loadView('pdf/invoice_pdf', $data);
        return $pdf->download('invoice_' . $id . '.pdf');


        
    }
}