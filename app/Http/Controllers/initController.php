<?php
namespace App\Http\Controllers;

use App\Models\cashers;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use App\Models\accounts;
use App\Models\items;
use App\Models\Branch;
use Exception;

class initController extends Controller
{
    public function index()
    {
        try {
            $branchs = Branch::all();
            $data = items::all();
            $data1 = accounts::all();
            $casher = cashers::all();
            $suppliers = Suppliers::all();
            return view('init', [
                'data' => $data,
                'data1' => $data1,
                'branchs' => $branchs,
                'casher' => $casher,
                'suppliers' => $suppliers
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب البيانات.');
        }
    }
}
