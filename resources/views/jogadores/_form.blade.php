        <div class="mb-3">
            <label class="form-label">Time</label>
            <select name="time_id" class="form-control select2">
                <option value="">Selecione</option>
                @foreach($times as $time)
                    <option value="{{ $time->id }}" {{ old('time_id', $jogador->time_id ?? '') == $time->id ? 'selected' : '' }}>{{ $time->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome', $jogador->nome ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nascimento</label>
            <input type="date" name="nascimento" class="form-control" value="{{ old('nascimento', isset($jogador->nascimento) ? $jogador->nascimento->format('Y-m-d') : '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Altura (m)</label>
            <input type="number" step="0.01" name="altura" class="form-control" value="{{ old('altura', $jogador->altura ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Peso (kg)</label>
            <input type="number" step="0.01" name="peso" class="form-control" value="{{ old('peso', $jogador->peso ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Posição</label>
            <input type="text" name="posicao" class="form-control" value="{{ old('posicao', $jogador->posicao ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Apto</label>
            <select name="apto" class="form-control">
                <option value="1" {{ old('apto', $jogador->apto ?? 1) ? 'selected' : '' }}>Sim</option>
                <option value="0" {{ old('apto', $jogador->apto ?? 1) ? '' : 'selected' }}>Não</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto</label>
            <input type="file" name="foto" class="form-control">
            @if(isset($jogador) && $jogador->foto)
                <img src="{{ asset('storage/'.$jogador->foto) }}" width="100" class="mt-2">
            @endif
        </div>

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.select2').select2({ width: '100%' });
        });
    </script>
@endpush
