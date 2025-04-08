<?php

use App\Http\Controllers\AjaxDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KostController;
use App\Http\Controllers\PenghuniController;
use App\Mail\ReminderMail;
use App\Models\Penghuni;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;



Auth::routes();


Route::get('/mail', function(){
  Mail::to('juandaent@gmail.com')->send(new ReminderMail)   ;
    return 'success';
});

Route::group(['middleware' => ['auth']], function(){
    Route::get('/', function () {
      return   redirect()->to('/dashboard');
    });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    
    Route::get('/ajax-list-penyewa',[PenghuniController::class, 'ajaxListPenghuni'])->name('ajax-list-penyewa');
    Route::resource('penyewa', PenghuniController::class);
    Route::get('/penghuni/data', [AjaxDashboardController::class, 'index'])->name('admin.penghuni.data');

    Route::post('/dashboard/update-status/{id}', [DashboardController::class, 'updateStatusPembayaran'])->name('update-status-pembayaran');
    
    Route::resource('dashboard', DashboardController::class);
    
    
    Route::get('/kamar/data', [KostController::class, 'ajaxListKost'])->name('ajax-list-kost');
    
    
    Route::post('/kamar/store', [KostController::class, 'storeKamar'])->name('store-kamar');
    Route::get('/ajaxSelectKamar', [KostController::class, 'ajaxSelectKamar'])->name('ajaxSelectKamar');
    
    Route::get('/ajaxSelectKamarPenyewa', [KostController::class, 'ajaxSelectKamarPenyewa'])->name('ajaxSelectKamarPenyewa');
    Route::get('/kamar/create', [KostController::class, 'createKamar'])->name('create-kamar');
    Route::get('/kost/edit/{id}', [KostController::class, 'edit'])->name('edit-kamar');
    Route::resource('kost', KostController::class);
});