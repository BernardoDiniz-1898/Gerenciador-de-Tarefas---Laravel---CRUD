<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TarefasController;
use Illuminate\Support\Facades\Route;

// Rotas de Autenticação (Públicas)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/cadastro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/cadastro', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redireciona a raiz para as tarefas
Route::redirect('/', '/tarefas');

// Rotas Protegidas (Só acessa se estiver logado)
Route::middleware('auth')->group(function () {
    Route::resource('tarefas', TarefasController::class)
        ->parameters(['tarefas' => 'tarefa'])
        ->except(['show']);
});
