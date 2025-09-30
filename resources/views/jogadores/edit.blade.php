@section('content')
    <div class="container">
        <h1>Editar Jogadores</h1>
        <form action="{{ route('jogadores.update', $jogadores) }}" method="POST">
            @csrf @method('PUT')
            @include('jogadores._form')
            <button type="submit" class="btn btn-success">Atualizar</button>
        </form>
    </div>
@endsection
