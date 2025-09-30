@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>{{ isset($time) ? 'Editar Time' : 'Novo Time' }}</h2>

        <form action="{{ isset($time) ? route('times.update', $time) : route('times.store') }}" method="POST">
            @csrf
            @if(isset($time))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome', $time->nome ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Score</label>
                <input type="number" name="score" class="form-control" value="{{ old('score', $time->score ?? 0) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ old('status', $time->status ?? 1) ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ old('status', $time->status ?? 1) ? '' : 'selected' }}>Inativo</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Localidade</label>
                <input type="text" name="localidade" class="form-control" value="{{ old('localidade', $time->localidade ?? '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Responsável</label>
                <input type="text" name="responsavel" class="form-control" value="{{ old('responsavel', $time->responsavel ?? '') }}">
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
@endsection
