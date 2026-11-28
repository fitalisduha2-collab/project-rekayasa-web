<?php

use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// ==============================
// HALAMAN PUBLIC
// ==============================

Route::get('/', function () {
    return view('page.home');
})->name('home');

Route::get('/about', function () {
    return view('page.about');
})->name('about');

Route::get('/profile', [MahasiswaController::class, 'index'])
    ->name('profile');

Route::get('/project', [ProjectController::class, 'index'])
    ->name('project.index');

Route::get('/project/{id}', [ProjectController::class, 'show'])
    ->name('project.show');

// ==============================
// AUTHENTICATION ADMIN
// ==============================

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');

// ==============================
// HALAMAN ADMIN (LOGIN REQUIRED)
// ==============================

Route::prefix('admin')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', function () {
        return view('admin.home');
    })->name('admin.dashboard');

    // Data Project
    Route::resource('projects', AdminProjectController::class)
        ->names('admin.projects');

    // Cetak PDF
    Route::get('/projects/pdf', [AdminProjectController::class, 'cetakPdf'])
        ->name('admin.projects.pdf');

    // Data Users
    Route::get('/users', function () {
        return view('admin.users');
    })->name('admin.users');

});