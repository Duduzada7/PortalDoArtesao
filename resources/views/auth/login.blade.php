<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Congonharte</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4efe9;
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
        }

        .brand-section {
            background-color: #c04918;
            color: #ffffff;
            width: 40%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-section a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
        }

        .brand-content {
            margin-bottom: auto;
            margin-top: auto;
        }

        .brand-title {
            font-size: 2.8rem;
            font-weight: 700;
            margin-top: 1rem;
            margin-bottom: 0.8rem;
        }

        .brand-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.5;
            max-width: 320px;
        }

        .form-section {
            width: 60%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .form-control-custom {
            background-color: #eee9e0;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }

        .form-control-custom:focus {
            background-color: #eee9e0;
            box-shadow: 0 0 0 2px #c04918;
        }

        .btn-terracota {
            background-color: #c04918;
            color: white;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 600;
            border: none;
            width: 100%;
        }

        .btn-terracota:hover {
            background-color: #a33c11;
            color: white;
        }

        .link-terracota {
            color: #c04918;
            text-decoration: none;
            font-weight: 600;
        }

        .link-terracota:hover {
            color: #a33c11;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .brand-section,
            .form-section {
                width: 100%;
            }

            .brand-section {
                padding: 2rem;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <!-- Lado Esquerdo - Branding -->
        <div class="brand-section">
            <div>
                <a href="/welcome">&larr; Voltar ao início</a>
            </div>
            <div class="brand-content">
                <div class="mb-3">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="#ffffff">
                        <path
                            d="M19 3H5c-1.1 0-2 .9-2 2v2c0 2.76 1.86 5.08 4.4 5.78C8 14.83 9.8 17 12 17s4-2.17 4.6-4.22C19.14 12.08 21 9.76 21 7V5c0-1.1-.9-2-2-2zm-1 4h-2V5h2v2zM6 5h2v2H6V5z" />
                    </svg>
                </div>
                <h1 class="brand-title">Congonharte</h1>
                <p class="brand-subtitle">Plataforma de gestão e valorização dos artesãos de Congonhas e região.</p>
            </div>
        </div>

        <!-- Lado Direito - Formulário -->
        <div class="form-section">
            <div class="login-card">
                <h3 class="fw-bold mb-1">Bem-vindo(a)</h3>
                <p class="text-muted small mb-4">Acesse sua conta para gerenciar seu perfil e inscrições.</p>

                @if(session('error'))
                    <div class="alert alert-danger py-2 mb-3 small">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="/login" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">E-mail</label>
                        <input type="Email" name="Email" class="form-control form-control-custom"
                            placeholder="seu@email.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small">Senha</label>
                        <input type="password" name="Senha" class="form-control form-control-custom"
                            placeholder="********" required>
                    </div>

                    <button type="submit" class="btn btn-terracota mb-3">Entrar</button>
                </form>

                <!-- Chamada para Cadastro -->
                <div class="text-center pt-2 border-top">
                    <span class="text-muted small">Ainda não tem uma conta?</span>
                    <a href="/artesao/cadastrar" class="link-terracota small ms-1">Cadastrar-se</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>