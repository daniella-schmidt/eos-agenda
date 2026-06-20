<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="profile-field">
        <label class="profile-label" for="name">Nome</label>
        <input
            id="name"
            class="profile-input"
            type="text"
            name="name"
            value="{{ old('name', $user->name) }}"
            required
            autofocus
            autocomplete="name"
        >
        @error('name')
            <span class="profile-input-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="profile-field">
        <label class="profile-label" for="email">E-mail</label>
        <input
            id="email"
            class="profile-input"
            type="email"
            name="email"
            value="{{ old('email', $user->email) }}"
            required
            autocomplete="username"
        >
        @error('email')
            <span class="profile-input-error">{{ $message }}</span>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="profile-notice profile-notice--warning">
                <span>Seu endere&ccedil;o de e-mail ainda n&atilde;o foi verificado.</span>
                <button
                    form="send-verification"
                    class="profile-btn profile-btn--ghost"
                    style="min-height: 32px; font-size: .8rem; padding: 0 10px; margin-left: auto;"
                >
                    Reenviar link
                </button>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="profile-notice">
                    Novo link de verifica&ccedil;&atilde;o enviado para o seu e-mail.
                </div>
            @endif
        @endif
    </div>

    <div class="profile-actions">
        <button type="submit" class="profile-btn profile-btn--primary">
            Salvar altera&ccedil;&otilde;es
        </button>

        @if (session('status') === 'profile-updated')
            <span
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2500)"
                class="profile-success"
            >Perfil atualizado com sucesso.</span>
        @endif
    </div>
</form>
