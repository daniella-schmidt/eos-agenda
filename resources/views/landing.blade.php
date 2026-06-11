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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Estilos do hero – use os mesmos do seu style.css ou adicione aqui */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(155deg, #e5ffff 0%, #d0feff 40%, #ffd6ec 100%);
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }
        .hero-content {
            text-align: center;
            max-width: 800px;
            z-index: 2;
        }
        .hero-logo {
            font-family: 'Righteous', cursive;
            font-size: 4rem;
            color: var(--teal, #00c1c4);
            margin-bottom: 1rem;
        }
        .hero-title {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            color: var(--dark, #0d2b2b);
        }
        .hero-description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: var(--dark, #0d2b2b);
            opacity: 0.8;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.75rem 2rem;
            font-weight: 800;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
        }
        .btn-primary {
            background: var(--teal, #00c1c4);
            color: white;
            border: 2px solid var(--dark, #0d2b2b);
        }
        .btn-outline {
            background: transparent;
            border: 2px solid var(--dark, #0d2b2b);
            color: var(--dark, #0d2b2b);
        }
        .btn-primary:hover {
            transform: translate(-2px, -2px);
            box-shadow: 4px 4px 0 var(--dark);
        }
    </style>
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