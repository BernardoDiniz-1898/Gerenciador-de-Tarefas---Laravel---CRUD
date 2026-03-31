<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TarefasController extends Controller
{
    public function index()
    {
        $tarefas = Tarefa::latest('created_at')->get();
        return view('tarefas.index', compact('tarefas'));
    }

    public function create()
    {
        return view('tarefas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'min:3', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['concluida', 'em andamento', 'incompleta'])],
            'data_finalizacao' => ['nullable', 'date'],
        ]);

        Tarefa::create($validated);

        return redirect()->route('tarefas.index');
    }

    public function edit(Tarefa $tarefa)
    {
        return view('tarefas.edit', compact('tarefa'));
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'min:3', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['concluida', 'em andamento', 'incompleta'])],
            'data_finalizacao' => ['nullable', 'date'],
        ]);

        $tarefa->update($validated);
        return redirect()->route('tarefas.index');
    }

    public function destroy(Tarefa $tarefa)
    {
        $tarefa->delete();
        return redirect()->route('tarefas.index');
    }
}
