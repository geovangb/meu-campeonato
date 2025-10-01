@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Iniciar Campeonato: {{ $campeonato->nome }}</h2>

        <ul class="nav nav-pills mb-3" id="steps">
            <li class="nav-item"><a class="nav-link active" data-step="1" href="#">1. Selecionar Times</a></li>
            <li class="nav-item"><a class="nav-link" data-step="2" href="#">2. Regras</a></li>
            <li class="nav-item"><a class="nav-link" data-step="3" href="#">3. Sorteios</a></li>
        </ul>

        <div id="steps-content">
            {{-- STEP 1 --}}
            <div id="step-1" class="step">
                <h4>Selecione os Times</h4>
                <form id="form-times">
                    <input type="hidden" name="campeonato_id" value="{{ $campeonato->id }}">
                    <select name="times[]" id="select-times" class="form-control" multiple required>
                        @foreach(\App\Models\Time::all() as $time)
                            <option value="{{ $time->id }}">{{ $time->nome }}</option>
                        @endforeach
                    </select>

                    <div class="mt-3">
                        <button type="button" class="btn btn-success" id="btn-add-time">+ Adicionar Novo Time</button>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Salvar Times</button>
                </form>
            </div>

            {{-- STEP 2 --}}
            <div id="step-2" class="step d-none">
                <h4>Regras do Campeonato</h4>
                <form id="form-step2">
                    <div class="mb-3">
                        <label class="form-label">Disputa de Penaltis</label>
                        <select name="penaltis" class="form-control">
                            <option value="1">Sim</option>
                            <option value="0" selected>Não</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prorrogação</label>
                        <select name="prorrogacao" class="form-control">
                            <option value="1">Sim</option>
                            <option value="0" selected>Não</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Critério de Desempate: Cartões</label>
                        <select name="criterio_desempate" class="form-control">
                            <option value="sim">Sim</option>
                            <option value="nao" selected>Não</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-primary mt-3" id="btn-save-step2">Salvar e Avançar</button>
                </form>
            </div>

            {{-- STEP 3 --}}
            <div id="step-3" class="step d-none">
                <h4>Sorteios</h4>
                <form id="form-step3">
                    <input type="hidden" name="campeonato_id" value="{{ $campeonato->id }}">
                    <button type="button" id="btn-sortear" class="btn btn-primary mb-3">Gerar Sorteios</button>
                </form>
                <div id="resultado-sorteio" class="mt-4"></div>
            </div>
        </div>

    @include('campeonatos.modal')

@endsection

@push('scripts')
    <script>
        const TIMES = @json(\App\Models\Time::all());
        const CAMPEONATO_ID = {{ $campeonato->id }};
        const MIN_TIMES = {{ $campeonato->qtd_times }};
    </script>
    <script src="{{ asset('js/starter.js') }}"></script>
@endpush
