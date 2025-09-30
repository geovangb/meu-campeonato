@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Times</h2>
        <a href="{{ route('times.create') }}" class="btn btn-primary mb-2">Novo Time</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Score</th>
                <th>Status</th>
                <th>Localidade</th>
                <th>Responsável</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($times as $time)
                <tr>
                    <td>{{ $time->nome }}</td>
                    <td>{{ $time->score }}</td>
                    <td>{{ $time->status ? 'Ativo' : 'Inativo' }}</td>
                    <td>{{ $time->localidade }}</td>
                    <td>{{ $time->responsavel }}</td>
                    <td>
                        <a href="{{ route('times.edit', $time) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('times.destroy', $time) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja excluir?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
