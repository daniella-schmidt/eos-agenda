/* Dashboard (Agenda Inteligente)
   - Smart request create/confirm
   - Busca local (filtrar .js-searchable)
*/

const smartRequestStoreUrl = '/api/smart-requests';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Formulário de comando para criar Smart Request
const smartCommandForm = document.getElementById('smartCommandForm');
if (smartCommandForm) {
    smartCommandForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const input = document.getElementById('smartCommandInput');
        const feedback = document.getElementById('smartCommandFeedback');
        const rawText = input?.value?.trim() || '';

        if (rawText.length < 5) {
            feedback.className = 'smart-feedback is-visible';
            feedback.textContent = 'Digite uma solicitacao com pelo menos 5 caracteres.';
            return;
        }

        feedback.className = 'smart-feedback is-visible';
        feedback.textContent = 'Analisando sua solicitacao...';

        try {
            const response = await fetch(smartRequestStoreUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ rawText }),
            });

            const payload = await response.json();

            if (!response.ok) {
                throw new Error(payload.message || 'Nao foi possivel registrar a solicitacao.');
            }

            const request = payload.data || payload;
            const status = request.status || 'pending';
            const needsConfirmation = status === 'needs_confirmation';
            const suggestingTimes = status === 'suggesting_times';

            feedback.innerHTML = `
                <div>
                    ${needsConfirmation
                        ? 'Encontrei dados suficientes. Deseja confirmar este evento?'
                        : suggestingTimes
                            ? 'Encontrei um conflito. Veja sugest&otilde;es de hor&aacute;rios alternativos.'
                            : 'Solicita&ccedil;&atilde;o registrada para an&aacute;lise.'}
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    ${needsConfirmation ? `<button type="button" class="eos-action eos-action--primary" data-confirm-request="${request.id}">Confirmar</button>` : ''}
                    <a class="eos-action eos-action--secondary" href="/smart-requests">Editar</a>
                    <a class="eos-action eos-action--secondary" href="/smart-requests">Ver sugest&otilde;es</a>
                </div>
            `;

            if (input) input.value = '';
        } catch (error) {
            feedback.className = 'smart-feedback is-visible';
            feedback.textContent = error?.message || String(error);
        }
    });
}

// Delegação de clique para confirmar Smart Request
// (o botão só existe após o submit, então precisa ser delegada)
document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-confirm-request]');
    if (!button) return;

    button.disabled = true;
    button.textContent = 'Confirmando...';

    try {
        const confirmRequestId = button.dataset.confirmRequest;

        const response = await fetch(`/api/smart-requests/${confirmRequestId}/confirm`, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        if (!response.ok) {
            const payload = await response.json().catch(() => ({}));
            throw new Error(payload.message || 'Nao foi possivel confirmar.');
        }

        button.textContent = 'Confirmado';
        window.setTimeout(() => window.location.reload(), 800);
    } catch (error) {
        button.disabled = false;
        button.textContent = 'Confirmar';

        const feedback = document.getElementById('smartCommandFeedback');
        if (feedback) feedback.textContent = error?.message || String(error);
    }
});

// Filtro por texto local (agenda/lista)
const dashboardSearch = document.getElementById('dashboardSearch');
if (dashboardSearch) {
    dashboardSearch.addEventListener('input', (event) => {
        const query = (event.target?.value || '').trim().toLowerCase();

        document.querySelectorAll('.js-searchable').forEach((item) => {
            const content = item.dataset.search || item.textContent.toLowerCase();
            item.hidden = query.length > 0 && !content.includes(query);
        });
    });
}

