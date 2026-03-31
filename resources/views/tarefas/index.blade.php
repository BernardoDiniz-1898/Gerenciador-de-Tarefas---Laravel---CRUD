@extends('layouts.app')

@section('title', 'Task Manager')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Task Manager</h2>
        <a href="{{ route('tarefas.create') }}" class="btn btn-primary fw-semibold px-4">
            <i class="fas fa-plus me-2"></i>Nova Tarefa
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">Nome da Tarefa</th>
                            <th>Status</th>
                            <th>Prazo</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tarefas as $tarefa)
                            <tr>
                                <td class="ps-4 py-3">
                                    <span class="d-block fw-semibold text-dark">{{ $tarefa->nome }}</span>
                                    @if($tarefa->descricao)
                                        <span class="small text-muted">{{ $tarefa->descricao }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tarefa->status === 'concluida')
                                        <span class="badge bg-success px-2 py-1">Concluída</span>
                                    @elseif($tarefa->status === 'em andamento')
                                        <span class="badge bg-warning text-dark px-2 py-1">Em andamento</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1">Incompleta</span>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    {{ $tarefa->data_finalizacao ? \Carbon\Carbon::parse($tarefa->data_finalizacao)->format('d/m/Y') : 'Sem prazo' }}
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('tarefas.edit', $tarefa->id) }}" class="btn btn-sm btn-outline-secondary me-1" title="Editar">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    
                                    <form action="{{ route('tarefas.destroy', $tarefa->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja apagar esta tarefa?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">Nenhuma tarefa cadastrada ainda. Que tal criar a primeira?</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection