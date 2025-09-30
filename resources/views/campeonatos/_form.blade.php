<div class="mb-3">
    <label class="form-label">Nome</label>
    <input type="text" name="nome" class="form-control" value="{{ old('nome', $campeonato->nome ?? '') }}" required>
    <input type="hidden" name="status" id="status" value="1">
</div>


<div class="mb-3">
    <label class="form-label">Data</label>
    <input type="date" name="data" class="form-control" value="{{ old('data', isset($campeonato->data) ? $campeonato->data->format('Y-m-d') : '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Tipo Campeonato</label>
    <select name="tipo_campeonato" class="form-control select2">
        @foreach (\App\Enums\TipoCampeonato::cases() as $tipo)
            <option value="{{ $tipo->value }}"
                {{ old('tipo_campeonato', $campeonato->tipo_campeonato ?? 'Copa Quartas de final (8 times)') == $tipo->value ? 'selected' : '' }}>
                {{ $tipo->value }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Qtd Times</label>
    <input type="number" name="qtd_times" readonly class="form-control" value="{{ old('qtd_times', $campeonato->qtd_times ?? 0) }}">
</div>

@push('scripts')
    <script src="{{ asset('js/campeonato.js') }}"></script>
@endpush
