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

    Route::get('/admin_produtos', [ProductController::class, 'adminIndex'])->name('admin_produtos');
    Route::get('/admin_produtos/dados/{id}', [ProductController::class, 'getDados'])->name('produtos.dados');
    Route::post('/admin_produtos', [ProductController::class, 'store'])->name('Adiciona_Produto');
    Route::put('/admin_produtos/{id}', [ProductController::class, 'update'])->name('Edita_Produto');
    Route::delete('/admin_produtos/{id}', [ProductController::class, 'destroy'])->name('Deleta_Produto');


    Route::get('/admin_usuarios', [App\Http\Controllers\UserController::class, 'adminIndex'])->name('admin_usuarios');
    Route::get('/admin_usuarios/dados/{id}', [App\Http\Controllers\UserController::class, 'getDados']);
    Route::post('/admin_usuarios', [App\Http\Controllers\UserController::class, 'store'])->name('usuarios.store');
    Route::put('/admin_usuarios/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/admin_usuarios/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('usuarios.destroy');

    // API ViaCEP
    Route::get('/api/cep/{cep}', [App\Http\Controllers\UserController::class, 'consultaCep']);

    // Compras
    Route::post('/compra/{id}', [App\Http\Controllers\CompraController::class, 'store'])->name('compra');
    Route::get('/historico', [App\Http\Controllers\CompraController::class, 'history'])->name('historico');

    Route::get('/perfil_pessoal', function () {
        return view('perfil_pessoal');
    })->name('perfil_pessoal');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
