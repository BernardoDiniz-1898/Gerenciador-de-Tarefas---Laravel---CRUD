<?php

use App\Http\Controllers\TarefasController;
use Illuminate\Support\Facades\Route;

Route::resource('tarefas', TarefasController::class)->parameters(['tarefas' => 'tarefa'])->except(['show']);
