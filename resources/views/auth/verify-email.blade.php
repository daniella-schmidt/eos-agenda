<x-eos-guest title="Verifique seu e-mail" :decoVariant="3">

    <div class="auth-sub">
        Obrigado por se cadastrar! Antes de começar, verifique seu e-mail clicando no link que enviamos. Se não recebeu, podemos reenviar.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="status-message" style="background: #d1fae5; color: #065f46;">
            Um novo link de verificação foi enviado para seu e-mail.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" style="margin-top: 20px;">
        @csrf
        <button type="submit" class="btn btn-main btn-block">Reenviar e-mail de verificação →</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 16px;">
        @csrf
        <button type="submit" class="btn btn-ghost btn-block" style="width:100%;">Sair</button>
    </form>
</x-eos-guest>