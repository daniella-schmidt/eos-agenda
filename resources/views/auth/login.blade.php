<x-eos-guest title="Entrar na sua conta" :decoVariant="1">

    @if (session('status'))
        <div class="status-message">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group">
            <label for="email" class="input-label">E-mail</label>
            <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="input-group">
            <label for="password" class="input-label">Senha</label>
            <input id="password" class="input-field" type="password" name="password" required autocomplete="current-password">
            @error('password') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="remember_me" name="remember">
            <label for="remember_me">Lembrar de mim</label>
        </div>

        <div class="flex-between">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-sm">Esqueceu sua senha?</a>
            @endif
            <button type="submit" class="btn btn-main" style="flex:1">Entrar →</button>
        </div>

        <div class="auth-footer">
            Não tem uma conta? <a href="{{ route('register') }}">Cadastre-se</a>
        </div>
    </form>
</x-eos-guest>