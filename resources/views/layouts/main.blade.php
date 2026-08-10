<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title> @yield('title') </title>
        <!-- Fontes -->
<link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet">
         <!-- Css bootsrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
          <!-- Css da aplicação -->
        
        <link rel="stylesheet" href="/css/styles.css">
        <script src="/js/scripts.js"> </script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a href="/" class="navbar-brand">
            <img src="/img/logo.jpg" alt="Logo" width="30" height="30">
        </a>
        <div class="collapse navbar-collapse show" id="navbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/Eventos/list" class="nav-link">Eventos</a>
                </li>
                <li class="nav-item">
                    <a href="/Eventos/create" class="nav-link">Criar Eventos</a>
                </li>
                <li class="nav-item">
                    <a href="/Eventos/delete" class="nav-link">Excluir Eventos</a>
                </li>
                <li class="nav-item">
                    <a href="/" class="nav-link">Cadastrar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    </header>
   <main>
    <div class="container-fluid">
        <div class="row">
            @if (session('msg'))
            <p class="msg">{{ session('msg') }}</p>
            @endif
             @yield('content')
        </div>
    </div>
   </main>
    <footer>
        <p>Plataforma &copy; 2026<p>
</footer>

<script type="module" src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@8.0.13/dist/ionicons/ionicons.js"></script>

</body>

</html>