@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Visão Geral - {{ $campeonato->nome }}</h2>

        {{-- Tabela do Pódio --}}
        <h4>Pódio</h4>
        <table class="table table-bordered text-center">
            <thead>
            <tr>
                <th>Campeão</th>
                <th>Vice</th>
                <th>3º Lugar</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $classificacao[0]['time']->nome ?? '-' }}</td>
                <td>{{ $classificacao[1]['time']->nome ?? '-' }}</td>
                <td>{{ $classificacao[2]['time']->nome ?? '-' }}</td>
            </tr>
            </tbody>
        </table>

        {{-- Classificação --}}
        <h4>Classificação</h4>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Time</th>
                <th>Jogos</th>
                <th>Pontos</th>
                <th>Gols Pró</th>
                <th>Gols Contra</th>
                <th>Saldo</th>
                <th>Cartões</th>
            </tr>
            </thead>
            <tbody>
            @foreach($classificacao as $i => $c)
                @php
                    $saldo = $c['gols_pro'] - $c['gols_contra'];
                    $jogosCount = $c['jogos'] ?? 0;
                @endphp
                <tr @if(in_array($c['time']->id, $top4)) class="table-success" @endif>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $c['time']->nome }}</td>
                    <td>{{ $jogosCount }}</td>
                    <td>{{ $c['pontos'] }}</td>
                    <td>{{ $c['gols_pro'] }}</td>
                    <td>{{ $c['gols_contra'] }}</td>
                    <td>{{ $saldo }}</td>
                    <td>{{ $c['cartoes'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Todos os Jogos --}}
        <h4>Jogos</h4>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Confronto</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($jogos as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $j->timeCasa->nome }} x {{ $j->timeFora->nome }}</td>
                    <td>{{ $j->data_partida?->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('jogos.edit', [$campeonato->id, $j->id]) }}" class="btn btn-sm btn-primary">Editar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Confrontos da Semifinal --}}
        <h4>Semifinais</h4>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Confronto</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($jogos->where('fase','semifinal') as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $j->timeCasa->nome }} x {{ $j->timeFora->nome }}</td>
                    <td>{{ $j->data_partida?->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('jogos.edit', [$campeonato->id, $j->id]) }}" class="btn btn-sm btn-primary">Editar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Confrontos da Final --}}
        <h4>Final</h4>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Confronto</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($jogos->where('fase','final') as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $j->timeCasa->nome }} x {{ $j->timeFora->nome }}</td>
                    <td>{{ $j->data_partida?->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('jogos.edit', [$campeonato->id, $j->id]) }}" class="btn btn-sm btn-primary">Editar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection
