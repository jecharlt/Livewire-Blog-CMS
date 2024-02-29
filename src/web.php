<?php

use Illuminate\Support\Facades\Auth;
use Jecharlt\LivewireBlogCMS\Livewire\Pages\AdminDashboard;
use Jecharlt\LivewireBlogCMS\Livewire\Pages\AdminLogin;
use Illuminate\Support\Facades\Route;
use Jecharlt\LivewireBlogCMS\Http\Controllers\ImageUploadController;

Route::middleware(['web'])->group(function() {
    Route::get('/blog-admin-login', AdminLogin::class)->name('blog-admin-login');
    Route::get('/blog-admin-dashboard', AdminDashboard::class)->name('blog-admin-dashboard');
    Route::get('/blog-admin-logout', function() {
        if (Auth::guard('blog')->check()) {
            Auth::guard('blog')->logout();
        }
        return redirect('/blog-admin-login');
    })->name('blog-admin-logout');
    Route::post('/blog-admin-image-upload', [ImageUploadController::class, 'upload'])->name('blog-admin-image-upload');
});
