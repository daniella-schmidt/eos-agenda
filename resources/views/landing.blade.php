{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EOS · Organize o amanhã</title>
    <meta name="description" content="EOS: organize o amanhã antes que ele aconteça.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&family=Righteous&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/landing.css'])

</head>
<body>
    <div class="hero">
        <div class="hero-content">
            <div class="hero-logo">EO<span style="color:#ff6bb3">S</span></div>
            <h1 class="hero-title">Organize o amanhã<br>antes que ele aconteça</h1>
            <p class="hero-description">Agenda inteligente com IA, lembretes automáticos e integração com seus calendários.</p>
            <div class="btn-group">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Ir para o Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Entrar</a>
                    <a href="{{ route('register') }}" class="btn btn-outline">Criar conta</a>
                @endauth
            </div>
        </div>
    </div>
</body>
</html>