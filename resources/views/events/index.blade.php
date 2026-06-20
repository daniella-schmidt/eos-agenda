<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        <style>
            .events-page {
                max-width: 1440px;
                margin: 0 auto;
                padding: 24px;
            }

            .events-card {
                background: #fff;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                box-shadow: 0 14px 35px rgba(13,43,43,.06);
            }

            .events-card__header {
                padding: 18px;
                border-bottom: 1px solid #dbe7e7;
            }

            .events-card__body {
                padding: 18px;
            }

            .events-eyebrow {
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .18em;
                text-transform: uppercase;
            }

            .events-title {
                margin-top: 4px;
                color: #0d2b2b;
                font-size: 1.35rem;
                font-weight: 900;
            }

            .events-heading {
                color: #0d2b2b;
                font-size: 1.8rem;
                font-weight: 900;
            }

            .events-muted {
                color: #647878;
                font-size: .9rem;
                font-weight: 600;
            }

            .events-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
                margin-bottom: 18px;
            }

            .events-search {
                width: 100%;
                border: 1px solid #cfe0e0;
                border-radius: 8px;
                background: #fff;
                color: #0d2b2b;
                padding: 10px 12px;
                font-size: .92rem;
                font-weight: 700;
                outline: none;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            .events-search:focus {
                border-color: #008f91;
                box-shadow: 0 0 0 3px rgba(0,143,145,.12);
            }

            .events-list-card {
                margin-bottom: 20px;
            }

            .events-list {
                display: flex;
                gap: 12px;
                overflow-x: auto;
                padding-bottom: 6px;
            }

            .event-item {
                flex: 0 0 340px;
                max-width: 340px;
                border: 1px solid #dbe7e7;
                border-left: 6px solid var(--event-color, #008f91);
                border-radius: 8px;
                background: #fff;
                padding: 14px;
                text-align: left;
                transition: border-color .15s ease, background .15s ease, transform .15s ease;
            }

            .event-item:hover,
            .event-item.is-selected {
                border-color: #008f91;
                background: #fafdff;
                transform: translateY(-1px);
            }

            .event-item__title {
                color: #0d2b2b;
                font-size: .98rem;
                font-weight: 900;
            }

            .event-item__meta {
                margin-top: 4px;
                color: #647878;
                font-size: .84rem;
                font-weight: 700;
            }

            .event-item__actions {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                margin-top: 12px;
            }

            .events-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                align-items: center;
            }

            .events-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                min-height: 40px;
                border-radius: 8px;
                border: 2px solid #0d2b2b;
                padding: 0 14px;
                font-size: .86rem;
                font-weight: 900;
                text-decoration: none;
                cursor: pointer;
                transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
            }

            .events-btn:hover {
                transform: translate(-1px,-1px);
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .events-btn:disabled {
                cursor: not-allowed;
                opacity: .55;
                transform: none;
                box-shadow: none;
            }

            .events-btn--primary {
                background: #008f91;
                color: #fff;
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .events-btn--ghost {
                background: #fff;
                color: #0d2b2b;
            }

            .events-btn--danger {
                background: #fff0f0;
                border-color: #c0392b;
                color: #c0392b;
            }

            .events-empty,
            .events-feedback {
                border: 1px dashed #cfe0e0;
                border-radius: 8px;
                padding: 16px;
                background: #fff;
                color: #647878;
                font-size: .9rem;
                font-weight: 700;
            }

            .events-feedback {
                display: none;
                border-style: solid;
                border-color: #b8eeee;
                background: #e5ffff;
                color: #0d2b2b;
                font-weight: 800;
                margin-bottom: 16px;
            }

            .events-feedback.is-visible { display: block; }

            .events-feedback.is-error {
                border-color: #f3b4b4;
                background: #fff0f0;
                color: #a32222;
            }

            .event-detail-overlay {
                position: fixed;
                inset: 0;
                z-index: 80;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
            }

            .event-detail-backdrop {
                position: absolute;
                inset: 0;
                background: rgba(13, 43, 43, .35);
                backdrop-filter: blur(3px);
            }

            .event-detail-modal {
                position: relative;
                width: min(760px, 100%);
                max-height: calc(100vh - 48px);
                overflow: auto;
                background: #ffffff;
                border: 1px solid #dbe7e7;
                border-radius: 12px;
                box-shadow: 0 24px 70px rgba(13, 43, 43, .22);
            }

            .event-detail-modal__header {
                position: sticky;
                top: 0;
                z-index: 2;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
                padding: 18px;
                border-bottom: 1px solid #dbe7e7;
                background: #ffffff;
            }

            .event-detail-modal__body { padding: 18px; }

            .event-detail-close {
                width: 38px;
                height: 38px;
                border-radius: 999px;
                border: 2px solid #0d2b2b;
                background: #ffffff;
                color: #0d2b2b;
                font-size: 1.4rem;
                font-weight: 900;
                line-height: 1;
                cursor: pointer;
            }

            .event-detail-close:hover {
                background: #fff0f0;
                color: #c0392b;
            }

            .detail-event {
                border: 1px solid #dbe7e7;
                border-left: 6px solid var(--event-color, #008f91);
                border-radius: 8px;
                padding: 16px;
                background: #fafdff;
            }

            .detail-event__top {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
            }

            .detail-event__time {
                color: #008f91;
                font-size: .9rem;
                font-weight: 900;
            }

            .detail-event__title {
                margin-top: 14px;
                color: #0d2b2b;
                font-size: 1.25rem;
                font-weight: 900;
            }

            .detail-event__description {
                margin-top: 10px;
                color: #526767;
                font-size: .95rem;
                font-weight: 600;
                line-height: 1.6;
            }

            .detail-event__info {
                margin-top: 16px;
                display: grid;
                gap: 10px;
                color: #647878;
                font-size: .9rem;
                font-weight: 800;
            }

            .detail-event__section {
                margin-top: 18px;
                padding-top: 14px;
                border-top: 1px solid #dbe7e7;
            }

            .detail-event__section-title {
                margin-bottom: 8px;
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .events-chip-list {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-top: 10px;
            }

            .events-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                border-radius: 999px;
                border: 1px solid #cfe0e0;
                background: #fff;
                color: #0d2b2b;
                padding: 6px 10px;
                font-size: .82rem;
                font-weight: 800;
            }

            .status-pill {
                display: inline-flex;
                align-items: center;
                border-radius: 999px;
                padding: 4px 9px;
                background: #e5ffff;
                color: #006b6d;
                font-size: .72rem;
                font-weight: 900;
            }

            .status-pill.is-high {
                background: #fff1f7;
                color: #b42369;
            }

            .status-pill.is-cancelled {
                background: #fff0f0;
                color: #c0392b;
            }

            .is-hidden { display: none !important; }

            @media (max-width: 1050px) {
                .event-item { flex-basis: 300px; }
            }
        </style>

        <div class="events-page">
            <div class="events-top">
                <div>
                    <h1 class="events-heading">Meus Eventos</h1>
                    <p class="events-muted">Gerencie compromissos, participantes e lembretes.</p>
                </div>
                <a href="{{ route('events.create') }}" class="events-btn events-btn--primary">
                    + Novo evento
                </a>
            </div>

            <div id="listFeedback" class="events-feedback" role="status"></div>

            <div class="mb-5">
                <input id="eventSearch" class="events-search" type="search"
                    placeholder="Buscar evento, participante, local ou status...">
            </div>

            <section class="events-card events-list-card">
                <div class="events-card__header">
                    <p class="events-eyebrow">Eventos</p>
                    <h2 class="events-title">Todos os eventos</h2>
                </div>
                <div class="events-card__body">
                    <div id="eventsList" class="events-list">
                        <div class="events-empty">Carregando eventos...</div>
                    </div>
                </div>
            </section>
        </div>

        <div id="eventDetailsOverlay" class="event-detail-overlay is-hidden">
            <div class="event-detail-backdrop" data-close-details></div>

            <section class="event-detail-modal">
                <div class="event-detail-modal__header">
                    <div>
                        <p class="events-eyebrow">Detalhes do evento</p>
                        <h2 id="eventDetailsTitle" class="events-title">Evento selecionado</h2>
                    </div>
                    <button class="event-detail-close" type="button" data-close-details>×</button>
                </div>
                <div id="eventDetailsContent" class="event-detail-modal__body">
                    <div class="events-empty">Carregando detalhes...</div>
                </div>
            </section>
        </div>

        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const editBaseUrl = "{{ url('/events') }}";

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

            const el = id => document.getElementById(id);

            const escapeHtml = value => String(value ?? '')
                .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');

            function formatDate(value) {
                return value
                    ? new Date(value).toLocaleString('pt-BR', {
                        day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit',
                    })
                    : '';
            }

            const statusLabel = s => ({ draft:'Rascunho', confirmed:'Confirmado', cancelled:'Cancelado' }[s] || s || '-');
            const priorityLabel = p => ({ low:'Baixa', medium:'Média', high:'Alta' }[p] || p || '-');

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

                const filtered = events.filter(event => {
                    const haystack = [
                        event.title, event.location, event.meetingURL,
                        event.calendar?.name, event.status, event.priority,
                        ...(event.participants || []).map(p => `${p.name} ${p.email}`),
                    ].join(' ').toLowerCase();
                    return !query || haystack.includes(query);
                });

                if (!filtered.length) {
                    el('eventsList').innerHTML = '<div class="events-empty">Nenhum evento encontrado.</div>';
                    return;
                }

                el('eventsList').innerHTML = filtered.map(event => `
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
                                    ? participants.map(p => `
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
                                    ? reminders.map(r => `
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
                                    onclick="deleteEventFromDetails('${event.id}')">
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
                    events = events.filter(e => String(e.id) !== String(id));
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

            el('eventSearch').addEventListener('input', renderEvents);

            el('eventsList').addEventListener('click', event => {
                const button = event.target.closest('[data-event-id]');
                if (!button) return;

                const id = button.dataset.eventId;

                if (button.dataset.action === 'delete') {
                    deleteEvent(id);
                } else if (button.dataset.action === 'details') {
                    showEventDetails(id);
                }
            });

            document.querySelectorAll('[data-close-details]').forEach(btn => {
                btn.addEventListener('click', closeDetailsModal);
            });

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeDetailsModal();
            });

            loadEvents().catch(error => {
                el('eventsList').innerHTML = `<div class="events-empty">${escapeHtml(error.message)}</div>`;
            });
        </script>
    </div>
</x-app-layout>
