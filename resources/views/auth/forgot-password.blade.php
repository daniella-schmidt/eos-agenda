<x-eos-guest title="Recuperar senha" :decoVariant="3">

    <div class="auth-sub">
        Esqueceu sua senha? Informe seu e-mail e enviaremos um link para redefinição.
    </div>

    @if (session('status'))
        <div class="status-message">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="input-group">
            <label for="email" class="input-label">E-mail</label>
            <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-main btn-block">Enviar link de redefinição →</button>

        <div class="auth-footer">
            <a href="{{ route('login') }}">Voltar para o login</a>
        </div>
    </form>
</x-eos-guest>