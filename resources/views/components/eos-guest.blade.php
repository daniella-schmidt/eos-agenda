@props(['title' => 'Acesse sua agenda', 'decoVariant' => 1])

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EOS — {{ $title }}</title>
    <meta name="description" content="EOS: organize o amanhã antes que ele aconteça.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&family=Righteous&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Estilos específicos para páginas de autenticação - mantendo o EOS vibe */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 24px 80px;
            background: linear-gradient(155deg, #e5ffff 0%, #d0feff 40%, #ffd6ec 100%);
            position: relative;
            overflow: hidden;
        }
        
        /* Elementos geométricos flutuantes - igual ao hero */
        .auth-deco {
            position: absolute;
            pointer-events: none;
            z-index: 0;
        }
        .deco-1 { top: 8%; left: 4%; animation: floatA 7s ease-in-out infinite; }
        .deco-2 { bottom: 12%; left: 3%; animation: floatA 5s ease-in-out 1s infinite; }
        .deco-3 { top: 15%; right: 4%; animation: floatB 6s ease-in-out 0.5s infinite; }
        .deco-4 { bottom: 8%; right: 5%; animation: floatA 8s ease-in-out 2s infinite; }
        .deco-5 { top: 40%; left: -2%; animation: floatB 9s ease-in-out 1.5s infinite; }
        .deco-6 { bottom: 20%; right: -1%; animation: floatA 7s ease-in-out 0.7s infinite; }
        
        /* Card de autenticação */
        .auth-card {
            max-width: 480px;
            width: 100%;
            background: var(--white);
            border: var(--border);
            border-radius: var(--radius);
            box-shadow: 8px 8px 0 var(--dark);
            padding: 40px 36px;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            z-index: 2;
        }
        .auth-card:hover {
            transform: translate(-2px, -2px);
            box-shadow: 10px 10px 0 var(--dark);
        }
        .auth-logo {
            font-family: 'Righteous', cursive;
            font-size: 2rem;
            color: var(--teal);
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 2px;
        }
        .auth-logo .dot { color: var(--pink); }
        .auth-title {
            font-family: 'Righteous', cursive;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 24px;
            color: var(--dark);
        }
        .auth-sub {
            text-align: center;
            margin-bottom: 28px;
            color: var(--dark);
            opacity: 0.7;
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-label {
            display: block;
            font-weight: 800;
            margin-bottom: 8px;
            color: var(--dark);
        }
        .input-field {
            width: 100%;
            padding: 12px 16px;
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
            border: var(--border);
            border-radius: 50px;
            background: var(--white);
            transition: all 0.2s;
        }
        .input-field:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px var(--teal-l);
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 16px 0;
        }
        .checkbox-group input {
            width: 18px;
            height: 18px;
            border: var(--border);
            border-radius: 4px;
            cursor: pointer;
        }
        .checkbox-group label {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--dark);
        }
        .auth-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 0.95rem;
            font-weight: 800;
        }
        .auth-footer a {
            color: var(--teal);
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: 0.15s;
            font-weight: 900;
        }
        .auth-footer a:hover {
            border-bottom-color: var(--teal);
            color: var(--teal-m);
        }
        .error-message {
            color: #e0508a;
            font-size: 0.75rem;
            font-weight: 800;
            margin-top: 6px;
            margin-left: 12px;
        }
        .status-message {
            background: var(--teal-l);
            border: var(--border);
            border-radius: 50px;
            padding: 10px 16px;
            text-align: center;
            font-weight: 800;
            font-size: 0.85rem;
            margin-bottom: 20px;
            color: var(--teal);
        }
        .btn-block {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 8px;
        }
        .link-sm {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--teal);
            text-decoration: none;
            transition: 0.15s;
        }
        .link-sm:hover {
            color: var(--teal-m);
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .auth-card { padding: 28px 20px; }
        }
        
        /* Animações iguais ao style.css */
        @keyframes floatA {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-14px) rotate(6deg); }
        }
        @keyframes floatB {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(-5deg); }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Elementos geométricos flutuantes - variantes alternadas -->
        @if($decoVariant == 1)
            <div class="auth-deco deco-1">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                    <rect x="4" y="4" width="56" height="56" rx="14" fill="#ffe14d" stroke="#0d2b2b" stroke-width="2.5" transform="rotate(15 32 32)"/>
                    <rect x="16" y="16" width="32" height="32" rx="8" fill="#ffb76b" stroke="#0d2b2b" stroke-width="2" transform="rotate(15 32 32)"/>
                </svg>
            </div>
            <div class="auth-deco deco-2">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                    <circle cx="24" cy="24" r="22" fill="#ff6bb3" stroke="#0d2b2b" stroke-width="2.5"/>
                    <circle cx="24" cy="24" r="12" fill="#ffd6ec" stroke="#0d2b2b" stroke-width="2"/>
                </svg>
            </div>
            <div class="auth-deco deco-3">
                <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                    <polygon points="28,4 52,48 4,48" fill="#ccfeff" stroke="#0d2b2b" stroke-width="2.5"/>
                    <polygon points="28,16 42,40 14,40" fill="#00c1c4" stroke="#0d2b2b" stroke-width="2"/>
                </svg>
            </div>
            <div class="auth-deco deco-4">
                <svg width="52" height="52" viewBox="0 0 52 52" fill="none">
                    <path d="M26 4 L48 26 L26 48 L4 26Z" fill="#ffe14d" stroke="#0d2b2b" stroke-width="2.5"/>
                    <path d="M26 14 L38 26 L26 38 L14 26Z" fill="#ffb76b" stroke="#0d2b2b" stroke-width="2"/>
                </svg>
            </div>
        @elseif($decoVariant == 2)
            <div class="auth-deco deco-1">
                <svg width="70" height="70" viewBox="0 0 70 70" fill="none">
                    <circle cx="35" cy="35" r="30" fill="#ffe14d" stroke="#0d2b2b" stroke-width="3"/>
                    <circle cx="35" cy="35" r="18" fill="#ffb76b" stroke="#0d2b2b" stroke-width="2"/>
                </svg>
            </div>
            <div class="auth-deco deco-3">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                    <rect x="5" y="5" width="50" height="50" rx="12" fill="#ff6bb3" stroke="#0d2b2b" stroke-width="2.5" transform="rotate(25 30 30)"/>
                    <rect x="18" y="18" width="24" height="24" rx="6" fill="#ccfeff" stroke="#0d2b2b" stroke-width="2" transform="rotate(25 30 30)"/>
                </svg>
            </div>
            <div class="auth-deco deco-5">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                    <polygon points="40,5 75,40 40,75 5,40" fill="#00c1c4" stroke="#0d2b2b" stroke-width="2.5"/>
                    <polygon points="40,20 60,40 40,60 20,40" fill="#ffe14d" stroke="#0d2b2b" stroke-width="2"/>
                </svg>
            </div>
        @elseif($decoVariant == 3)
            <div class="auth-deco deco-2">
                <svg width="55" height="55" viewBox="0 0 55 55" fill="none">
                    <path d="M27.5 5L50 27.5L27.5 50L5 27.5Z" fill="#ffb76b" stroke="#0d2b2b" stroke-width="2.5"/>
                    <circle cx="27.5" cy="27.5" r="12" fill="#ffe14d" stroke="#0d2b2b" stroke-width="2"/>
                </svg>
            </div>
            <div class="auth-deco deco-4">
                <svg width="65" height="65" viewBox="0 0 65 65" fill="none">
                    <rect x="8" y="8" width="49" height="49" rx="16" fill="#ff6bb3" stroke="#0d2b2b" stroke-width="2.5"/>
                    <circle cx="32.5" cy="32.5" r="20" fill="#ccfeff" stroke="#0d2b2b" stroke-width="2"/>
                </svg>
            </div>
            <div class="auth-deco deco-6">
                <svg width="90" height="90" viewBox="0 0 90 90" fill="none">
                    <polygon points="45,10 80,45 45,80 10,45" fill="#00c1c4" stroke="#0d2b2b" stroke-width="2"/>
                    <polygon points="45,25 60,45 45,65 30,45" fill="#ffe14d" stroke="#0d2b2b" stroke-width="1.5"/>
                </svg>
            </div>
        @else
            <!-- fallback com estrelas -->
            <div class="auth-deco deco-1">
                <svg width="50" height="50" viewBox="0 0 50 50" fill="none">
                    <polygon points="25,5 30,20 45,20 33,30 38,45 25,35 12,45 17,30 5,20 20,20" fill="#ffe14d" stroke="#0d2b2b" stroke-width="2"/>
                </svg>
            </div>
            <div class="auth-deco deco-3">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                    <circle cx="30" cy="30" r="25" fill="#ffb76b" stroke="#0d2b2b" stroke-width="2"/>
                    <circle cx="30" cy="30" r="12" fill="#ff6bb3" stroke="#0d2b2b" stroke-width="2"/>
                </svg>
            </div>
        @endif

        <div class="auth-card">
            <div class="auth-logo">
                EO<span class="dot">S</span>
            </div>
            <h1 class="auth-title">{{ $title }}</h1>
            {{ $slot }}
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>