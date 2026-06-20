<p class="profile-desc" style="margin-top: 0;">
    Uma vez exclu&iacute;da, sua conta e todos os dados associados ser&atilde;o removidos permanentemente.
    Fa&ccedil;a o download de qualquer informa&ccedil;&atilde;o que deseja manter antes de prosseguir.
</p>

<div class="profile-actions">
    <button
        type="button"
        class="profile-btn profile-btn--danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        Excluir minha conta
    </button>
</div>

<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" style="padding: 24px;">
        @csrf
        @method('delete')

        <h2 style="font-size: 1.1rem; font-weight: 900; color: #a32222;">
            Tem certeza que deseja excluir sua conta?
        </h2>

        <p style="margin-top: 8px; font-size: .88rem; font-weight: 600; color: #647878; line-height: 1.5;">
            Esta a&ccedil;&atilde;o &eacute; permanente e irrevers&iacute;vel. Todos os seus dados ser&atilde;o removidos.
            Digite sua senha para confirmar.
        </p>

        <div class="profile-field" style="margin-top: 20px; margin-bottom: 0;">
            <label class="profile-label" for="delete_password">Sua senha</label>
            <input
                id="delete_password"
                class="profile-input"
                type="password"
                name="password"
                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
            >
            @error('password', 'userDeletion')
                <span class="profile-input-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
            <button
                type="button"
                class="profile-btn profile-btn--ghost"
                x-on:click="$dispatch('close')"
            >
                Cancelar
            </button>

            <button type="submit" class="profile-btn profile-btn--danger">
                Confirmar exclus&atilde;o
            </button>
        </div>
    </form>
</x-modal>
