(() => {
    const container = document.getElementById('calendar-events');
    const statusBadge = document.getElementById('status');

    if (!container || !statusBadge) return;

    const statusLabels = { draft: 'Rascunho', confirmed: 'Confirmado', cancelled: 'Cancelado' };
    const priorityLabels = { low: 'Baixa', medium: 'Média', high: 'Alta' };

    function formatDate(value) {
        return value
            ? new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeStyle: 'short' }).format(new Date(value))
            : 'Sem data';
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '<')
            .replace(/>/g, '>')
            .replace(/"/g, '"');
    }

    async function loadEvents() {
        try {
            // endpoint: /api/calendars/{id}/events
            // id vem via data attribute.
            const calId = container.dataset.calendarId;
            const response = await fetch(`/api/calendars/${calId}/events`, {
                headers: { Accept: 'application/json' },
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data?.message || 'Não foi possível carregar os eventos.');

            const events = data.data ?? [];
            statusBadge.textContent = `${events.length} ${events.length === 1 ? 'evento' : 'eventos'}`;

            if (!events.length) {
                container.innerHTML = '<div class="rounded bg-gray-50 px-4 py-8 text-center text-sm text-gray-600">Nenhum evento neste calendário ainda.</div>';
                return;
            }

            container.innerHTML = events
                .map((event) => `
                    <article class="rounded border border-gray-200 p-4">
                        <h4 class="font-semibold text-gray-900">${escapeHtml(event.title)}</h4>
                        <p class="mt-1 text-sm text-gray-600">${formatDate(event.startAt)} até ${formatDate(event.endAt)}</p>
                        ${event.description ? `<p class="mt-3 text-sm leading-6 text-gray-700">${escapeHtml(event.description)}</p>` : ''}
                        <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold">
                            <span class="rounded bg-blue-50 px-2.5 py-1 text-blue-800">${statusLabels[event.status] ?? event.status}</span>
                            <span class="rounded bg-gray-100 px-2.5 py-1 text-gray-700">Prioridade ${priorityLabels[event.priority] ?? event.priority}</span>
                        </div>
                    </article>
                `)
                .join('');
        } catch (error) {
            statusBadge.textContent = 'Erro';
            container.innerHTML = `<div class="rounded bg-red-50 px-4 py-6 text-sm text-red-800">${escapeHtml(error.message)}</div>`;
        }
    }

    loadEvents();
})();

