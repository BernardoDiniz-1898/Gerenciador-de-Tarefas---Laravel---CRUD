<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TarefasController extends Controller
{
    public function index()
    {
        $tarefas = Tarefa::where('user_id', Auth::id())->latest()->get();
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

        $dados = $request->all();
        $dados['user_id'] = Auth::id();
        Tarefa::create($dados);

        return redirect()->route('tarefas.index');
    }

    public function edit(Tarefa $tarefa)
    {
        if ($tarefa->user_id !== Auth::id())
            abort(403, 'Acesso negado.');
        return view('tarefas.edit', compact('tarefa'));
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        if ($tarefa->user_id !== Auth::id())
            abort(403, 'Acesso negado.');
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
        if ($tarefa->user_id !== Auth::id())
            abort(403, 'Acesso negado.');

        $tarefa->delete();
        return redirect()->route('tarefas.index');
    }
}
