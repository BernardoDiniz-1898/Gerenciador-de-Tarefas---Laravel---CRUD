@extends('layouts.app')

@section('title', 'Editar Tarefa')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Editar Tarefa</h2>
        <a href="{{ route('tarefas.index') }}" class="btn btn-outline-secondary px-4">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('tarefas.update', $tarefa->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="nome" class="form-label fw-semibold">Nome da Tarefa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome', $tarefa->nome) }}" required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="data_finalizacao" class="form-label fw-semibold">Prazo (Opcional)</label>
                        <input type="date" class="form-control @error('data_finalizacao') is-invalid @enderror" id="data_finalizacao" name="data_finalizacao" 
                               value="{{ old('data_finalizacao', $tarefa->data_finalizacao ? \Carbon\Carbon::parse($tarefa->data_finalizacao)->format('Y-m-d') : '') }}">
                        @error('data_finalizacao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label fw-semibold">Descrição (Opcional)</label>
                    <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="3">{{ old('descricao', $tarefa->descricao) }}</textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="incompleta" {{ old('status', $tarefa->status) == 'incompleta' ? 'selected' : '' }}>Incompleta</option>
                        <option value="em andamento" {{ old('status', $tarefa->status) == 'em andamento' ? 'selected' : '' }}>Em andamento</option>
                        <option value="concluida" {{ old('status', $tarefa->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-5 fw-bold">
                        <i class="fas fa-sync-alt me-2"></i>Atualizar Tarefa
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection