@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Campeonatos</h1>
        <a href="{{ route('campeonatos.create') }}" class="btn btn-primary mb-3">Novo Campeonato</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Status</th>
                <th>Data</th>
                <th>Qtd Times</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($campeonatos as $c)
                <tr>
                    <td>{{ $c->nome }}</td>
                    <td>{{ $c->status ? 'Ativo' : 'Inativo' }}</td>
                    <td>{{ $c->data?->format('d/m/Y') }}</td>
                    <td>{{ $c->qtd_times }}</td>
                    <td>{{ $c->tipo_campeonato->value }}</td>
                    <td>
                        <a href="{{ route('campeonatos.edit', $c) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('campeonatos.destroy', $c) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                        </form>
                        <a href="{{ route('campeonatos.starter', $c) }}" class="btn btn-sm btn-success">Iniciar</a>
                        <a href="{{ route('campeonatos.jogos.visao_geral', $c) }}" class="btn btn-sm btn-primary">Ver</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $campeonatos->links() }}
    </div>
@endsection
