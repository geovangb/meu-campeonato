
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Novo Campeonato</h1>
        <form action="{{ route('campeonatos.store') }}" method="POST">
            @csrf
            @include('campeonatos._form')
            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
    </div>
@endsection
