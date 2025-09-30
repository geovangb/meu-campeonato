@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Jogadores</h2>
        <a href="{{ route('jogadores.create') }}" class="btn btn-primary mb-2">Novo Jogador</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Time</th>
                <th>Nascimento</th>
                <th>Altura</th>
                <th>Peso</th>
                <th>Posição</th>
                <th>Apto</th>
                <th>Foto</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($jogadores as $j)
                <tr>
                    <td>{{ $j->nome }}</td>
                    <td>{{ $j->time->nome }}</td>
                    <td>{{ $j->nascimento?->format('d/m/Y') }}</td>
                    <td>{{ $j->altura }}</td>
                    <td>{{ $j->peso }}</td>
                    <td>{{ $j->posicao }}</td>
                    <td>{{ $j->apto ? 'Sim' : 'Não' }}</td>
                    <td>
                        @if($j->foto)
                            <img src="{{ asset('storage/'.$j->foto) }}" width="50" alt="Foto">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('jogadores.edit', $j) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('jogadores.destroy', $j) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja excluir?')">
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
