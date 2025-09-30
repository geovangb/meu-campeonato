@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Campeonato</h1>
        <form action="{{ route('campeonatos.update', $campeonato) }}" method="POST">
            @csrf @method('PUT')
            @include('campeonatos._form')
            <button type="submit" class="btn btn-success">Atualizar</button>
        </form>
    </div>
@endsection
