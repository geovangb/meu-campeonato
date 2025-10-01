@extends('layouts.app')

@section('content')
    <div class="container" id="jogo-data" data-campeonato-id="{{ $jogo->campeonato_id }}" data-jogo-id="{{ $jogo->id }}">
        <h2>Gerenciar Jogo: {{ $jogo->timeCasa->nome }} x {{ $jogo->timeFora->nome }}</h2>

        <div class="mb-3">
            <label class="form-label">Data do Jogo</label>
            <input type="datetime-local" id="data-jogo" class="form-control" value="{{ $jogo->data_partida ? $jogo->data_partida->format('Y-m-d\TH:i') : '' }}">
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <h4>{{ $jogo->timeCasa->nome }} <span id="pontos-casa">0</span> pts</h4>
                <label>Gols</label>
                <input type="number" id="gols-casa" class="form-control" value="{{ $jogo->gols_casa ?? 0 }}">
            </div>
            <div class="col-md-6">
                <h4>{{ $jogo->timeFora->nome }} <span id="pontos-fora">0</span> pts</h4>
                <label>Gols</label>
                <input type="number" id="gols-fora" class="form-control" value="{{ $jogo->gols_fora ?? 0 }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <h5>Juiz / Auxiliares</h5>
                <input type="text" id="juiz" class="form-control mb-1" placeholder="Juiz" value="{{ $jogo->juiz }}">
                <input type="text" id="auxiliar-1" class="form-control mb-1" placeholder="Auxiliar 1" value="{{ $jogo->auxiliar_1 }}">
                <input type="text" id="auxiliar-2" class="form-control" placeholder="Auxiliar 2" value="{{ $jogo->auxiliar_2 }}">
            </div>
        </div>

        <div class="row">
            {{-- Time Casa --}}
            <div class="col-md-6">
                <h5>{{ $jogo->timeCasa->nome }} - Titulares</h5>
                <div id="casa-escalacao" class="escala border p-2 mb-2" style="min-height:100px;"></div>
                <h5>{{ $jogo->timeCasa->nome }} - Reservas</h5>
                <div id="casa-reservas" class="reservas border p-2 mb-2" style="min-height:80px;"></div>
            </div>

            {{-- Time Fora --}}
            <div class="col-md-6">
                <h5>{{ $jogo->timeFora->nome }} - Titulares</h5>
                <div id="fora-escalacao" class="escala border p-2 mb-2" style="min-height:100px;"></div>
                <h5>{{ $jogo->timeFora->nome }} - Reservas</h5>
                <div id="fora-reservas" class="reservas border p-2 mb-2" style="min-height:80px;"></div>
            </div>
        </div>

        <button type="button" class="btn btn-success mt-3" id="btn-salvar-jogo">Salvar Jogo</button>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/jogos/edit.js') }}"></script>
    <script>
        const TIME_CASA = @json($jogo->timeCasa->jogadores);
        const TIME_FORA = @json($jogo->timeFora->jogadores);
        const ESCALACAO_CASA = @json($jogo->escalacao_time_1 ?? []);
        const RESERVAS_CASA = @json($jogo->reservas_time_1 ?? []);
        const ESCALACAO_FORA = @json($jogo->escalacao_time_2 ?? []);
        const RESERVAS_FORA = @json($jogo->reservas_time_2 ?? []);
    </script>
@endpush
