@extends('layouts.main')

@section('title', 'Portal do Artesao')


@section('content')
    <div>
        <nav> 
            <h3>Congonharte</h3>
        </nav>
        <button type="Button">
            Entrar
        </button>

    </div>

    <div class="container-apresentacao">
        <p>🏺</p><br>
        <h1>Artesãos de Congonhas</h1>
        <p>Descubra o talento e a tradição dos artesãos da região do Alto Paraopeba. Conecte-se diretamente com os criadores.</p>
    </div>
    
    <search>
        <form action="/search" method="get">
            <label for="art-search">Buscar por artesão</label>
            <input type="search" id="artesao-busca" name="q" placeholder="Buscar por nome, especialidade, cidade...">
            <button type="submit">Buscar</button>
        </form>
    </search>
@endsection

