<x-eos-guest title="Criar conta" :decoVariant="2">

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="input-group">
            <label for="name" class="input-label">Nome completo</label>
            <input id="name" class="input-field" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="input-group">
            <label for="email" class="input-label">E-mail</label>
            <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="input-group">
            <label for="password" class="input-label">Senha</label>
            <input id="password" class="input-field" type="password" name="password" required autocomplete="new-password">
            @error('password') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="input-group">
            <label for="password_confirmation" class="input-label">Confirmar senha</label>
            <input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-main btn-block" style="width:100%; margin-top: 8px;">Cadastrar →</button>

        <div class="auth-footer">
            Já tem cadastro? <a href="{{ route('login') }}">Faça login</a>
        </div>
    </form>
</x-eos-guest>