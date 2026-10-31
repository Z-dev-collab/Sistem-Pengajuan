<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
Route::get('/layanan/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/layanan/{service}/formulir', [ServiceController::class, 'downloadForm'])->name('services.form');
Route::get('/cari', SearchController::class)->name('search');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');

Route::get('/pengajuan', [SubmissionController::class, 'create'])->name('submissions.create');
Route::post('/pengajuan', [SubmissionController::class, 'store'])->name('submissions.store');
Route::get('/pengajuan/{code}/berhasil', [SubmissionController::class, 'success'])->name('submissions.success');
