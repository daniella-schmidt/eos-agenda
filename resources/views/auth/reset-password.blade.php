<x-eos-guest title="Redefinir senha" :decoVariant="1">

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="input-group">
            <label for="email" class="input-label">E-mail</label>
            <input id="email" class="input-field" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="input-group">
            <label for="password" class="input-label">Nova senha</label>
            <input id="password" class="input-field" type="password" name="password" required autocomplete="new-password">
            @error('password') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="input-group">
            <label for="password_confirmation" class="input-label">Confirmar nova senha</label>
            <input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-main btn-block">Redefinir senha →</button>
    </form>
</x-eos-guest>