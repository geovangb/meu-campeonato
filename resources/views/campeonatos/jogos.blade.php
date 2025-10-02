@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Jogos - Campeonato {{ $campeonato->nome }}</h2>

    <table class="table table-striped mb-4">
        <thead>
        <tr>
            <th>#</th>
            <th>Time Casa</th>
            <th>Time Fora</th>
            <th>Data</th>
            <th>Gols</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        @foreach($jogos as $jogo)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $jogo->timeCasa->nome }}</td>
            <td>{{ $jogo->timeFora->nome }}</td>
            <td>{{ $jogo->data_partida?->format('d/m/Y H:i') }}</td>
            <td>{{ $jogo->gols_casa ?? '-' }} x {{ $jogo->gols_fora ?? '-' }}</td>
            <td>
                <a href="{{ route('jogos.edit', [$campeonato->id, $jogo->id]) }}" class="btn btn-sm btn-primary">Editar</a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <h3>Tabela de Pontuação</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Posição</th>
            <th>Time</th>
            <th>Pontos</th>
            <th>Gols Pró</th>
            <th>Gols Contra</th>
            <th>Cartões</th>
        </tr>
        </thead>
        <tbody>
        @foreach($times as $index => $time)
        <tr @if(in_array($time->id, $classificados)) class="table-success" @endif>
            <td>{{ $index + 1 }}</td>
            <td>{{ $time->nome }}</td>
            <td>{{ $time->pontos }}</td>
            <td>{{ $time->gols_pro }}</td>
            <td>{{ $time->gols_contra }}</td>
            <td>{{ $time->cartoes }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
