<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="profile-field">
        <label class="profile-label" for="update_password_current_password">Senha atual</label>
        <input
            id="update_password_current_password"
            class="profile-input"
            type="password"
            name="current_password"
            autocomplete="current-password"
        >
        @error('current_password', 'updatePassword')
            <span class="profile-input-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="profile-field">
        <label class="profile-label" for="update_password_password">Nova senha</label>
        <input
            id="update_password_password"
            class="profile-input"
            type="password"
            name="password"
            autocomplete="new-password"
        >
        @error('password', 'updatePassword')
            <span class="profile-input-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="profile-field">
        <label class="profile-label" for="update_password_password_confirmation">Confirmar nova senha</label>
        <input
            id="update_password_password_confirmation"
            class="profile-input"
            type="password"
            name="password_confirmation"
            autocomplete="new-password"
        >
        @error('password_confirmation', 'updatePassword')
            <span class="profile-input-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="profile-actions">
        <button type="submit" class="profile-btn profile-btn--primary">
            Alterar senha
        </button>

        @if (session('status') === 'password-updated')
            <span
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2500)"
                class="profile-success"
            >Senha atualizada com sucesso.</span>
        @endif
    </div>
</form>
