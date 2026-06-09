<x-eos-guest title="Confirme sua senha" :decoVariant="2">

    <div class="auth-sub">
        Esta é uma área segura. Por favor, confirme sua senha antes de continuar.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="input-group">
            <label for="password" class="input-label">Senha</label>
            <input id="password" class="input-field" type="password" name="password" required autocomplete="current-password">
            @error('password') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-main btn-block">Confirmar →</button>
    </form>
</x-eos-guest>