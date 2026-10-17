<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos e Feiras · Congonharte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <style>
        body { background: #f7f4f0; font-family: 'Outfit', sans-serif; color: #2a1a12; margin: 0; }
        .comm-header { background: #c04918; color: #fff; padding: 40px 20px; text-align: center; }
        .comm-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        .comm-header p { font-size: 14px; opacity: 0.9; }
        .comm-container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .comm-back { display: inline-block; margin-bottom: 20px; font-size: 13px; font-weight: 600; color: #c04918; text-decoration: none; }
        .comm-back:hover { text-decoration: underline; }
        .comm-card { background: #fff; border: 1px solid #ece5de; border-radius: 16px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .comm-card h3 { font-size: 18px; font-weight: 700; color: #c04918; margin-bottom: 10px; }
        .comm-info { font-size: 13px; color: #6b5d55; line-height: 1.6; margin-bottom: 12px; }
        .comm-desc { font-size: 13px; color: #2a1a12; line-height: 1.6; border-top: 1px solid #ece5de; padding-top: 12px; margin-top: 12px; }
        .comm-empty { text-align: center; padding: 40px; color: #6b5d55; background: #fff; border-radius: 16px; border: 1px dashed #ece5de; }
    </style>
</head>
<body>

    <div class="comm-header">
        <h1>Feiras e Eventos Culturais</h1>
        <p>Acompanhe a programação oficial de exposições e eventos de artesanato em Congonhas.</p>
    </div>

    <div class="comm-container">
        <a href="/" class="comm-back">&larr; Voltar à página inicial</a>

        @forelse($eventos as $evento)
            <div class="comm-card">
                <h3>{{ $evento->Nome }}</h3>
                <div class="comm-info">
                    <strong>📅 Data de Início:</strong> {{ $evento->Dia ? date('d/m/Y H:i', strtotime($evento->Dia)) : 'A definir' }}<br>
                    @if($evento->DataFim)
                        <strong>📅 Término:</strong> {{ date('d/m/Y H:i', strtotime($evento->DataFim)) }}<br>
                    @endif
                    <strong>📍 Local:</strong> 
                    {{ $evento->Bairro ? ($evento->Rua ? $evento->Rua . ', ' . ($evento->Numero ? $evento->Numero . ' - ' : '') : '') . $evento->Bairro : ($evento->Localizacao ?? 'Congonhas, MG') }}
                </div>
                
                @if($evento->Descricao)
                    <div class="comm-desc">
                        {{ $evento->Descricao }}
                    </div>
                @endif
            </div>
        @empty
            <div class="comm-empty">
                Não há eventos abertos ou programados no momento. Volte em breve!
            </div>
        @endforelse
    </div>

</body>
</html>