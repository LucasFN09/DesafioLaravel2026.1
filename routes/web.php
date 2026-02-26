<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    
    
    Route::get('/', [ProductController::class, 'index'])->name('home');

    
    Route::get('/produto/{id}', [ProductController::class, 'show'])->name('produto_individual');

    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin_produtos', function () {
        return view('admin_produtos');
    })->name('admin_produtos');

    Route::get('/admin_usuarios', function () {
        return view('admin_usuarios');
    })->name('admin_usuarios');

    Route::get('/perfil_pessoal', function () {
        return view('perfil_pessoal');
    })->name('perfil_pessoal');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';