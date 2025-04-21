<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\initController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\CasherController;
use App\Http\Controllers\CasherProcController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\ReceiptsController;
use App\Http\Controllers\EmpsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\pdfController;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');


})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  //التهية
    Route::get('/init',[initController::class,'index'])->name('init.index');
    //التفاصيل
    Route::get('/details',[DetailsController::class,'index'])->name('details.index');
    Route::post('/details/store', [DetailsController::class, 'store'])->name('details.store');
    Route::post('/details/update', [DetailsController::class, 'update'])->name('details.update');
    Route::get('/details/destroy/{id}', [DetailsController::class, 'destroy'])->name('details.destroy');
   //الاصناف
    Route::post('/items', [ItemsController::class, 'store'])->name('items.store');
    Route::post('/items/update', [ItemsController::class, 'update'])->name('items.update');
    Route::get('/items/destroy/{id}', [ItemsController::class, 'destroy'])->name('items.destroy');
   

   //الفروع
    Route::post('/branch', [BranchController::class, 'store'])->name('branch.store');
    Route::post('/branch/update', [BranchController::class, 'update'])->name('branch.update');
    Route::get('/branch/destroy/{id}', [BranchController::class, 'destroy'])->name('branch.destroy');

    //الحسابات
    Route::post('/account', [AccountsController::class, 'store'])->name('account.store');
    Route::post('/account/update', [AccountsController::class, 'update'])->name('account.update');
    Route::get('/account/destroy/{id}', [AccountsController::class, 'destroy'])->name('account.destroy');

//التقارير
Route::get('/reports',[ReportsController::class,'index'])->name('reports.index');
Route::post('/reports/monthly', [ReportsController::class, 'monthly'])->name('reports.monthly');
Route::post('/reports/inventory', [ReportsController::class, 'inventory'])->name('reports.inventory');
Route::post('/reports/casher', [ReportsController::class, 'casher'])->name('reports.casher');
Route::post('/reports/branch', [ReportsController::class, 'branch'])->name('reports.branch');
Route::post('/reports/total', [ReportsController::class, 'total'])->name('reports.total');
Route::get('/reports/opreation_sys',[ReportsController::class,'opreation_sys'])->name('reports.opreation_sys');

//الكاشير
Route::post('/casher', [CasherController::class, 'store'])->name('casher.store');
Route::post('/casher/update', [CasherController::class, 'update'])->name('casher.update');
Route::get('/casher/destroy/{id}', [CasherController::class, 'destroy'])->name('casher.destroy');

//عمليات الكاشير
Route::get('/casher_proc',[CasherProcController::class,'index'])->name('casher_proc.index');
Route::post('/casher_proc', [CasherProcController::class, 'store'])->name('casher_proc.store');
Route::post('/casher_proc/update', [CasherProcController::class, 'update'])->name('casher_proc.update');
Route::get('/casher_proc/destroy/{id}', [CasherProcController::class, 'destroy'])->name('casher_proc.destroy');

Route::post('/supplier/store', [SuppliersController::class, 'store'])->name('supplier.store');
Route::post('/supplier/update', [SuppliersController::class, 'update'])->name('supplier.update');
Route::get('/supplier/destroy/{id}', [SuppliersController::class, 'destroy'])->name('supplier.destroy');


// التوريد
Route::get('/supplier', [SuppliersController::class, 'index'])->name('supplier.index');
Route::get('/supplier/pay', [SuppliersController::class, 'pay'])->name('supplier.pay');
Route::post('/supplier/storeSupply', [SuppliersController::class, 'storeSupply'])->name('supplier.storeSupply');
Route::post('/supplier/updateSupply', [SuppliersController::class, 'updateSupply'])->name('supplier.updateSupply');
Route::post('/supplier/det2', [SuppliersController::class, 'det2'])->name('supplier.det2');
Route::get('/supplier/deleteSupply/{id}', [SuppliersController::class, 'deleteSupply'])->name('supplier.deleteSupply');
Route::get('/supplier/printSupply/{id}', [SuppliersController::class, 'printSupply'])->name('supplier.printSupply');


Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::post('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// السنداتتتتتت
Route::post('/pay/storepay', [PayController::class, 'storepay'])->name('pay.storepay');
Route::get('/pay', [PayController::class, 'index'])->name('pay.index');
Route::post('/pay/updatePay', [PayController::class, 'updatePay'])->name('pay.updatePay');
Route::get('/pay/destroy/{id}', [PayController::class, 'destroy'])->name('pay.destroy');
Route::get('/pay/printpay/{id}', [PayController::class, 'printpay'])->name('pay.printpay');

//سند قبض
Route::get('/receipt', [ReceiptsController::class, 'index'])->name('receipt.index');
Route::post('/receipt/store', [ReceiptsController::class, 'store'])->name('receipt.store');
Route::get('/receipt/printreceipt/{id}', [ReceiptsController::class, 'printreceipt'])->name('receipt.printreceipt');

//الموظفين
Route::get('/emps', [EmpsController::class, 'index'])->name('emps.index');
Route::post('/emps/store', [EmpsController::class, 'store'])->name('emps.store');
Route::post('/emps/update', [EmpsController::class, 'update'])->name('emps.update');
Route::get('/emps/destroy/{id}', [EmpsController::class, 'destroy'])->name('emps.destroy');
Route::get('/emps/print/{id}', [EmpsController::class, 'print'])->name('emps.print');
Route::post('/emps/dis', [EmpsController::class, 'dis'])->name('emps.dis');
Route::get('/emps/zeros', [EmpsController::class, 'zeros'])->name('emps.zeros');
Route::get('/emps/print_report', [EmpsController::class, 'print_report'])->name('emps.print_report');

//الفواتير
Route::get('/invoices', [InvoicesController::class, 'index'])->name('invoices.index');
Route::get('invoice_details', [InvoicesController::class, 'invoice_details'])->name('invoices.invoice_details');
Route::post('/invoices/store', [InvoicesController::class, 'store'])->name('invoices.store');
Route::post('/invoices/update', [InvoicesController::class, 'update'])->name('invoices.update');
Route::get('/invoices/destroy/{id}', [InvoicesController::class, 'destroy'])->name('invoices.destroy');
Route::get('/invoices/print/{id}', [InvoicesController::class, 'print'])->name('invoices.print');
Route::post('/invoices/details', [InvoicesController::class, 'details'])->name('invoices.details');
Route::post('/invoices/update_detail', [InvoicesController::class, 'update_detail'])->name('invoices.update_detail');
Route::get('/invoices/destroy_detail/{id}', [InvoicesController::class, 'destroy_detail'])->name('invoices.destroy_detail');
Route::get('/invoices/show/{id}', [InvoicesController::class, 'show'])->name('invoices.show');

//المخازن
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::post('/inventory/store', [InventoryController::class, 'store'])->name('inventory.store');
Route::post('/inventory/update', [InventoryController::class, 'update'])->name('inventory.update');
Route::get('/inventory/destroy/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

//pdf
Route::get('/generate-pdf/{id}', [pdfController::class, 'generatePDF'])->name('generatePDF');



});

require __DIR__.'/auth.php';
