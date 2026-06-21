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

// Lembretes futuros
const remindersContainer = document.getElementById('upcomingRemindersContainer');
if (remindersContainer) {
    const typeLabels = {
        notification: 'Notificação',
        email: 'E-mail',
        whatsapp: 'WhatsApp',
    };

    function formatDateTime(isoString) {
        if (!isoString) return '-';
        const date = new Date(isoString);
        return date.toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' });
    }

    function renderReminder(reminder) {
        const startAt = reminder.event?.startAt ? new Date(reminder.event.startAt) : null;
        const notificationAt = startAt
            ? new Date(startAt.getTime() - reminder.minutesBefore * 60 * 1000)
            : null;

        const title = reminder.event?.title || 'Evento sem título';
        const calendarName = reminder.event?.calendar?.name || 'Sem calendário';
        const sentLabel = reminder.isSent ? 'Concluído' : 'Pendente';
        const sentClass = reminder.isSent ? '' : 'is-high';
        const typeLabel = typeLabels[reminder.type] || reminder.type;
        const notificationLabel = notificationAt ? formatDateTime(notificationAt.toISOString()) : '-';

        return `
            <div class="block rounded-lg border border-[#dbe7e7] p-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-black text-[#0d2b2b]">${title}</p>
                        <p class="mt-1 truncate text-xs font-bold text-gray-500">${calendarName}</p>
                    </div>
                    <span class="status-pill ${sentClass}">${sentLabel}</span>
                </div>
                <div class="mt-3 grid gap-1 text-xs font-bold text-gray-500">
                    <span>Avisar ${reminder.minutesBefore} min antes</span>
                    <span>${notificationLabel} &middot; ${typeLabel}</span>
                </div>
            </div>
        `;
    }

    fetch('/api/event-reminders/upcoming', {
        headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
        .then((res) => {
            if (!res.ok) {
                console.error('[reminders] HTTP', res.status, res.url);
                throw new Error(`HTTP ${res.status}`);
            }
            return res.json();
        })
        .then((payload) => {
            console.log('[reminders] payload', payload);
            const reminders = (payload.data ?? payload).slice(0, 5);

            if (reminders.length === 0) {
                remindersContainer.innerHTML = `
                    <div class="rounded-lg border border-dashed border-[#cfe0e0] p-4 text-sm font-semibold text-gray-500">
                        Nenhum lembrete futuro configurado.
                    </div>`;
                return;
            }

            remindersContainer.innerHTML = reminders.map(renderReminder).join('');
        })
        .catch((err) => {
            console.error('[reminders] fetch error', err);
            remindersContainer.innerHTML = `
                <div class="rounded-lg border border-dashed border-[#cfe0e0] p-4 text-sm font-semibold text-gray-500">
                    Não foi possível carregar os lembretes.
                </div>`;
        });
}

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

