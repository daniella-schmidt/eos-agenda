@php
    $nameParts = explode(' ', auth()->user()->name ?? '');
    $initials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1));
    if (count($nameParts) > 1) {
        $last = $nameParts[count($nameParts) - 1];
        if ($last) {
            $initials .= strtoupper(substr($last, 0, 1));
        }
    }
@endphp

<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        <style>
            .profile-page {
                max-width: 860px;
                margin: 0 auto;
                padding: 24px;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .profile-card {
                background: #fff;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                box-shadow: 0 14px 35px rgba(13, 43, 43, .06);
            }

            .profile-hero {
                display: flex;
                align-items: center;
                gap: 20px;
                padding: 24px;
                flex-wrap: wrap;
            }

            .profile-avatar {
                width: 80px;
                height: 80px;
                border-radius: 999px;
                background: #008f91;
                border: 3px solid #0d2b2b;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.6rem;
                font-weight: 900;
                color: #fff;
                flex-shrink: 0;
                user-select: none;
                letter-spacing: -.02em;
            }

            .profile-hero__info {
                min-width: 0;
                flex: 1;
            }

            .profile-hero__eyebrow {
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .18em;
                text-transform: uppercase;
                color: #008f91;
            }

            .profile-hero__name {
                font-size: 1.35rem;
                font-weight: 900;
                color: #0d2b2b;
                margin-top: 2px;
            }

            .profile-hero__email {
                font-size: .9rem;
                font-weight: 600;
                color: #647878;
                margin-top: 2px;
            }

            .profile-card__header {
                padding: 18px 24px;
                border-bottom: 1px solid #dbe7e7;
            }

            .profile-card__body {
                padding: 24px;
            }

            .profile-eyebrow {
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .18em;
                text-transform: uppercase;
                color: #008f91;
            }

            .profile-title {
                font-size: 1.1rem;
                font-weight: 900;
                color: #0d2b2b;
                margin-top: 2px;
            }

            .profile-desc {
                font-size: .88rem;
                font-weight: 600;
                color: #647878;
                margin-top: 6px;
            }

            .profile-field {
                display: flex;
                flex-direction: column;
                gap: 6px;
                margin-bottom: 18px;
            }

            .profile-label {
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .12em;
                text-transform: uppercase;
                color: #008f91;
            }

            .profile-input {
                width: 100%;
                border: 1px solid #cfe0e0;
                border-radius: 8px;
                background: #fff;
                color: #0d2b2b;
                padding: 10px 12px;
                font-size: .92rem;
                font-weight: 700;
                outline: none;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            .profile-input:focus {
                border-color: #008f91;
                box-shadow: 0 0 0 3px rgba(0, 143, 145, .12);
            }

            .profile-input-error {
                font-size: .82rem;
                font-weight: 700;
                color: #c0392b;
            }

            .profile-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                min-height: 40px;
                border-radius: 8px;
                border: 2px solid #0d2b2b;
                padding: 0 16px;
                font-size: .86rem;
                font-weight: 900;
                cursor: pointer;
                text-decoration: none;
                background: none;
                transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
            }

            .profile-btn:hover {
                transform: translate(-1px, -1px);
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .profile-btn--primary {
                background: #008f91;
                color: #fff;
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .profile-btn--ghost {
                background: #fff;
                color: #0d2b2b;
            }

            .profile-btn--danger {
                background: #fff0f0;
                border-color: #c0392b;
                color: #c0392b;
                box-shadow: 3px 3px 0 #c0392b;
            }

            .profile-btn--danger:hover {
                box-shadow: 4px 4px 0 #c0392b;
                transform: translate(-1px, -1px);
            }

            .profile-btn--photo {
                background: #fff;
                border: 1px solid #cfe0e0;
                border-radius: 8px;
                color: #647878;
                font-size: .82rem;
                font-weight: 700;
                min-height: 36px;
                padding: 0 14px;
                cursor: not-allowed;
                opacity: .65;
            }

            .profile-btn--photo:hover {
                transform: none;
                box-shadow: none;
            }

            .profile-danger-card {
                background: #fff8f8;
                border: 1px solid #f3b4b4;
                border-radius: 8px;
                box-shadow: 0 14px 35px rgba(192, 57, 43, .04);
            }

            .profile-danger-card .profile-card__header {
                border-bottom-color: #f3b4b4;
            }

            .profile-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                align-items: center;
                margin-top: 20px;
            }

            .profile-notice {
                display: flex;
                align-items: center;
                gap: 10px;
                border: 1px solid #b8eeee;
                background: #e5ffff;
                border-radius: 8px;
                padding: 10px 14px;
                font-size: .88rem;
                font-weight: 700;
                color: #0d2b2b;
                margin-top: 10px;
                flex-wrap: wrap;
            }

            .profile-notice--warning {
                border-color: #f3d9a4;
                background: #fffbf0;
                color: #7a4e00;
            }

            .profile-success {
                font-size: .85rem;
                font-weight: 700;
                color: #006b6d;
            }

            .profile-page-top {
                padding-bottom: 4px;
            }
        </style>

        <div class="profile-page">

            <div class="profile-page-top">
                <p class="profile-eyebrow">Conta</p>
                <h1 style="font-size: 1.8rem; font-weight: 900; color: #0d2b2b; margin-top: 4px;">Configura&ccedil;&otilde;es do perfil</h1>
            </div>

            {{-- Hero card --}}
            <div class="profile-card">
                <div class="profile-hero">
                    <div class="profile-avatar">{{ $initials }}</div>

                    <div class="profile-hero__info">
                        <p class="profile-hero__eyebrow">Conta ativa</p>
                        <h2 class="profile-hero__name">{{ auth()->user()->name }}</h2>
                        <p class="profile-hero__email">{{ auth()->user()->email }}</p>
                    </div>

                    <button type="button" class="profile-btn profile-btn--photo" disabled title="Funcionalidade em breve">
                        Alterar foto
                    </button>
                </div>
            </div>

            {{-- Profile information --}}
            <div class="profile-card">
                <div class="profile-card__header">
                    <p class="profile-eyebrow">Informa&ccedil;&otilde;es pessoais</p>
                    <h2 class="profile-title">Dados do perfil</h2>
                    <p class="profile-desc">Atualize seu nome e endere&ccedil;o de e-mail.</p>
                </div>
                <div class="profile-card__body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Password --}}
            <div class="profile-card">
                <div class="profile-card__header">
                    <p class="profile-eyebrow">Seguran&ccedil;a</p>
                    <h2 class="profile-title">Alterar senha</h2>
                    <p class="profile-desc">Use uma senha longa e aleat&oacute;ria para manter sua conta segura.</p>
                </div>
                <div class="profile-card__body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Danger zone --}}
            <div class="profile-danger-card">
                <div class="profile-card__header">
                    <p class="profile-eyebrow" style="color: #c0392b;">Zona de perigo</p>
                    <h2 class="profile-title" style="color: #a32222;">Excluir conta</h2>
                    <p class="profile-desc">A&ccedil;&atilde;o permanente e irrevers&iacute;vel.</p>
                </div>
                <div class="profile-card__body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
