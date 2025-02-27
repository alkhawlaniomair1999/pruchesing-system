<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\initController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AccountsController;



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
   //الاصناف
    Route::post('/items', [ItemsController::class, 'store'])->name('items.store');
   

   //الفروع
    Route::post('/branch', [BranchController::class, 'store'])->name('branch.store');
    Route::post('/account', [AccountsController::class, 'store'])->name('account.store');



});

require __DIR__.'/auth.php';
