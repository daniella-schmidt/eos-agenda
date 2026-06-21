(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const root = document.getElementById('events-page-root');
    const editBaseUrl = root?.dataset?.editBaseUrl || window.__EVENTS_EDIT_BASE_URL__ || '';


    let events = [];
    let selectedEvent = null;

    async function api(url, options = {}) {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            ...options,
        });

        if (response.status === 204) return null;

        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(payload.message || 'Não foi possível concluir a operação.');
        }

        return payload;
    }

    const el = (id) => document.getElementById(id);

    const escapeHtml = (value) => String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '<')
        .replace(/>/g, '>')
        .replace(/\"/g, '"');

    function formatDate(value) {
        return value
            ? new Date(value).toLocaleString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            })
            : '';
    }

    const statusLabel = (s) => ({
        draft: 'Rascunho',
        confirmed: 'Confirmado',
        cancelled: 'Cancelado',
    }[s] || s || '-');

    const priorityLabel = (p) => ({
        low: 'Baixa',
        medium: 'Média',
        high: 'Alta',
    }[p] || p || '-');

    function showFeedback(message, type = 'success') {
        el('listFeedback').textContent = message;
        el('listFeedback').className = `events-feedback is-visible ${type === 'error' ? 'is-error' : ''}`;
    }

    function openDetailsModal() {
        el('eventDetailsOverlay').classList.remove('is-hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailsModal() {
        el('eventDetailsOverlay').classList.add('is-hidden');
        document.body.style.overflow = '';
    }

    async function loadEvents() {
        const response = await api('/api/events?perPage=100');
        events = response.data || [];
        renderEvents();
    }

    function renderEvents() {
        const query = el('eventSearch').value.trim().toLowerCase();

        const filtered = events.filter((event) => {
            const haystack = [
                event.title,
                event.location,
                event.meetingURL,
                event.calendar?.name,
                event.status,
                event.priority,
                ...(event.participants || []).map((p) => `${p.name} ${p.email}`),
            ].join(' ').toLowerCase();
            return !query || haystack.includes(query);
        });

        if (!filtered.length) {
            el('eventsList').innerHTML = '<div class="events-empty">Nenhum evento encontrado.</div>';
            return;
        }

        el('eventsList').innerHTML = filtered.map((event) => `
            <article class="event-item ${selectedEvent?.id === event.id ? 'is-selected' : ''}"
                     style="--event-color:${event.calendar?.color || '#008f91'}">
                <div class="event-item__title">${escapeHtml(event.title)}</div>
                <div class="event-item__meta">${formatDate(event.startAt)}</div>
                <div class="event-item__meta">
                    ${escapeHtml(event.location || event.meetingURL || event.calendar?.name || 'Sem local')}
                </div>
                <div class="event-item__meta">
                    ${escapeHtml(statusLabel(event.status))} |
                    ${escapeHtml(priorityLabel(event.priority))} |
                    ${event.participantsCount ?? event.participants?.length ?? 0} participante(s) |
                    ${event.remindersCount ?? event.reminders?.length ?? 0} lembrete(s)
                </div>
                <div class="event-item__actions">
                    <button class="events-btn events-btn--ghost" type="button"
                            data-action="details" data-event-id="${event.id}">
                        Detalhes
                    </button>
                    <a href="${editBaseUrl}/${event.id}/edit" class="events-btn events-btn--ghost">
                        Editar
                    </a>
                    <button class="events-btn events-btn--danger" type="button"
                            data-action="delete" data-event-id="${event.id}">
                        Excluir
                    </button>
                </div>
            </article>
        `).join('');
    }

    async function fetchEvent(id) {
        const response = await api(`/api/events/${id}`);
        selectedEvent = response.data;
        renderEvents();
        return selectedEvent;
    }

    async function showEventDetails(id) {
        const event = await fetchEvent(id);
        renderEventDetailsCard(event);
        openDetailsModal();
    }

    function renderEventDetailsCard(event) {
        const participants = event.participants || [];
        const reminders = event.reminders || [];
        const statusClass = event.status === 'cancelled' ? 'is-cancelled' : '';
        const priorityClass = event.priority === 'high' ? 'is-high' : '';

        el('eventDetailsTitle').textContent = event.title || 'Evento selecionado';

        el('eventDetailsContent').innerHTML = `
            <article class="detail-event" style="--event-color:${event.calendar?.color || '#008f91'}">
                <div class="detail-event__top">
                    <div class="detail-event__time">
                        ${formatDate(event.startAt)} até ${formatDate(event.endAt)}
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:4px;">
                        <span class="status-pill ${statusClass}">${escapeHtml(statusLabel(event.status))}</span>
                        <span class="status-pill ${priorityClass}">Prioridade ${escapeHtml(priorityLabel(event.priority))}</span>
                    </div>
                </div>

                <h3 class="detail-event__title">${escapeHtml(event.title || '-')}</h3>

                <p class="detail-event__description">
                    ${event.description ? escapeHtml(event.description) : 'Sem descrição cadastrada.'}
                </p>

                <div class="detail-event__info">
                    <p><strong>Calendário:</strong> ${escapeHtml(event.calendar?.name || '-')}</p>
                    <p><strong>Local:</strong> ${escapeHtml(event.location || '-')}</p>
                    <p><strong>Reunião:</strong> ${
                        event.meetingURL
                            ? `<a href="${escapeHtml(event.meetingURL)}" target="_blank" style="color:#008f91">${escapeHtml(event.meetingURL)}</a>`
                            : '-'
                    }</p>
                    <p><strong>Fuso horário:</strong> ${escapeHtml(event.timezone || '-')}</p>
                    ${event.isAllDay ? '<p><strong>Tipo:</strong> Evento de dia todo</p>' : ''}
                    ${event.isRecurring ? '<p><strong>Recorrência:</strong> Evento recorrente</p>' : ''}
                </div>

                <div class="detail-event__section">
                    <p class="detail-event__section-title">Participantes</p>
                    <div class="events-chip-list">
                        ${participants.length
                            ? participants.map((p) => `
                                <span class="events-chip">
                                    ${escapeHtml(p.name || p.email || 'Participante')}
                                    ${p.email ? ` · ${escapeHtml(p.email)}` : ''}
                                    · ${escapeHtml(p.role || 'attendee')}
                                    · ${escapeHtml(p.responseStatus || 'pending')}
                                </span>`).join('')
                            : '<div class="events-empty">Nenhum participante.</div>'
                        }
                    </div>
                </div>

                <div class="detail-event__section">
                    <p class="detail-event__section-title">Lembretes</p>
                    <div class="events-chip-list">
                        ${reminders.length
                            ? reminders.map((r) => `
                                <span class="events-chip">
                                    ${escapeHtml(r.type || 'notification')} · ${Number(r.minutesBefore)} min antes
                                </span>`).join('')
                            : '<div class="events-empty">Nenhum lembrete.</div>'
                        }
                    </div>
                </div>

                <div class="events-actions" style="margin-top:20px;">
                    <a href="${editBaseUrl}/${event.id}/edit" class="events-btn events-btn--ghost">
                        Editar evento
                    </a>
                    <button class="events-btn events-btn--danger" type="button"
                            onclick="window.deleteEventFromDetails?.('${event.id}')">
                        Excluir
                    </button>
                </div>
            </article>
        `;
    }

    async function deleteEvent(id) {
        if (!id || !confirm('Excluir este evento? Participantes e lembretes vinculados também serão removidos.')) return;

        try {
            await api(`/api/events/${id}`, { method: 'DELETE' });
            events = events.filter((e) => String(e.id) !== String(id));
            if (selectedEvent && String(selectedEvent.id) === String(id)) selectedEvent = null;
            renderEvents();
            showFeedback('Evento excluído com sucesso.');
        } catch (error) {
            showFeedback(error.message, 'error');
        }
    }

    async function deleteEventFromDetails(id) {
        await deleteEvent(id);
        closeDetailsModal();
    }

    // Mantém compatibilidade com onclick gerado pelo template
    window.deleteEventFromDetails = deleteEventFromDetails;

    const searchEl = el('eventSearch');
    if (searchEl) searchEl.addEventListener('input', renderEvents);

    const eventsListEl = el('eventsList');
    if (eventsListEl) {
        eventsListEl.addEventListener('click', (event) => {
            const button = event.target.closest('[data-event-id]');
            if (!button) return;

            const id = button.dataset.eventId;

            if (button.dataset.action === 'delete') {
                deleteEvent(id);
            } else if (button.dataset.action === 'details') {
                showEventDetails(id);
            }
        });
    }

    document.querySelectorAll('[data-close-details]').forEach((btn) => {
        btn.addEventListener('click', closeDetailsModal);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDetailsModal();
    });

    loadEvents().catch((error) => {
        const eventsList = el('eventsList');
        if (eventsList) {
            eventsList.innerHTML = `<div class="events-empty">${escapeHtml(error.message)}</div>`;
        }
    });
})();

