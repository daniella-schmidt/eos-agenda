// events/create logic (migrado do inline do Blade)
(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    let calendars = [];
    let contacts = [];
    let smartRequests = [];
    let eventSuggestions = [];
    let selectedParticipants = [];
    let selectedReminders = [];

    const el = (id) => document.getElementById(id);
    const nullable = (value) => value.trim() || null;
    const escapeHtml = (value) => String(value ?? '').replace(/&/g,'&amp;').replace(/</g,'<').replace(/>/g,'>').replace(/\"/g,'"');
    const pad = (n) => String(n).padStart(2, '0');

    const toLocalInput = (value) => {
        if (!value) return '';
        const d = new Date(value);
        return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    };

    const formatDate = (value) => (
        value
            ? new Date(value).toLocaleString('pt-BR', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' })
            : ''
    );

    const api = async (url, options = {}) => {
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
    };

    const showFeedback = (message, type = 'success') => {
        el('formFeedback').textContent = message;
        el('formFeedback').className = `events-feedback is-visible ${type === 'error' ? 'is-error' : ''}`;
    };

    const clearFeedback = () => {
        el('formFeedback').textContent = '';
        el('formFeedback').className = 'events-feedback';
    };

    async function loadBootstrap() {
        const [calendarResponse, contactResponse] = await Promise.all([
            api('/api/calendars'),
            api('/api/contacts?perPage=100'),
        ]);

        calendars = calendarResponse.data || [];
        contacts = contactResponse.data || [];

        el('calendarId').innerHTML = calendars
            .filter(c => c.isActive)
            .map(c => `<option value="${c.id}" ${c.isDefault ? 'selected' : ''}>${escapeHtml(c.name)}</option>`)
            .join('');

        el('contactSelect').innerHTML = '<option value="">Selecionar contato...</option>' + contacts
            .map(c => `<option value="${c.id}">${escapeHtml(c.name)}${c.email ? ` - ${escapeHtml(c.email)}` : ''}</option>`)
            .join('');
    }

    async function loadEventSuggestions() {
        el('eventSuggestionsList').innerHTML = '<div class="events-empty">Carregando sugestões...</div>';

        const statuses = ['suggesting_times', 'needs_confirmation'];
        const requestResponses = await Promise.all(statuses.map((s) => api(`/api/smart-requests/status/${s}`)));

        const requestMap = new Map();
        smartRequests = requestResponses
            .flatMap((r) => r.data || [])
            .filter((r) => {
                if (requestMap.has(r.id)) return false;
                requestMap.set(r.id, r);
                return true;
            });

        const suggestionResponses = await Promise.all(
            smartRequests.map((req) => api(`/api/smart-requests/${req.id}/suggestions`).then((r) => ({ request: req, suggestions: r.data || [] })))
        );

        eventSuggestions = suggestionResponses
            .flatMap((g) => g.suggestions.map((s) => ({ ...s, smartRequest: g.request })))
            .sort((a, b) =>
                Number(b.selected) - Number(a.selected) ||
                Number(b.score || 0) - Number(a.score || 0) ||
                new Date(a.suggestedStartAt) - new Date(b.suggestedStartAt)
            );

        renderEventSuggestions();
    }

    function renderEventSuggestions() {
        if (!eventSuggestions.length) {
            el('eventSuggestionsList').innerHTML = '<div class="events-empty">Nenhuma sugestão encontrada.</div>';
            return;
        }

        el('eventSuggestionsList').innerHTML = eventSuggestions.map((suggestion) => {
            const request = suggestion.smartRequest || {};
            const title = request.extractedTitle || request.rawText || `Sugestão #${suggestion.id}`;

            return `
                <article class="event-suggestion-item ${suggestion.selected ? 'is-selected' : ''}">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;">
                        <div>
                            <div class="event-suggestion-item__title">${escapeHtml(title)}</div>
                            <div class="event-suggestion-item__meta">
                                ${formatDate(suggestion.suggestedStartAt)} até ${formatDate(suggestion.suggestedEndAt)}
                            </div>
                            <div class="event-suggestion-item__meta">Score ${Number(suggestion.score || 0).toFixed(1)}</div>
                        </div>
                        <span class="status-pill ${suggestion.selected ? '' : 'is-high'}">
                            ${suggestion.selected ? 'Selecionada' : 'Sugestão'}
                        </span>
                    </div>
                    ${suggestion.reason ? `<p class="event-suggestion-item__reason">${escapeHtml(suggestion.reason)}</p>` : ''}
                    <div class="events-actions mt-3">
                        <button class="events-btn events-btn--ghost" type="button" data-action="use-suggestion" data-suggestion-id="${suggestion.id}">
                            Usar horário
                        </button>
                    </div>
                </article>
            `;
        }).join('');
    }

    async function useEventSuggestion(id) {
        const suggestion = eventSuggestions.find((s) => String(s.id) === String(id));
        if (!suggestion) return;

        await api(`/api/event-suggestions/${id}/select`, { method: 'POST' });

        eventSuggestions = eventSuggestions.map((s) => ({ ...s, selected: String(s.id) === String(id) }));
        renderEventSuggestions();

        const selected = suggestion;
        el('startAt').value = toLocalInput(selected.suggestedStartAt);
        el('endAt').value = toLocalInput(selected.suggestedEndAt);

        const request = suggestion.smartRequest || {};
        if (!el('title').value && (request.extractedTitle || request.rawText)) {
            el('title').value = request.extractedTitle || request.rawText;
        }
        if (!el('description').value && request.extractedDescription) {
            el('description').value = request.extractedDescription;
        }

        showFeedback('Horário sugerido aplicado ao formulário.');
    }

    function renderParticipants() {
        if (!selectedParticipants.length) {
            el('participantsList').innerHTML = '<span style="color:#647878;font-size:.9rem;font-weight:600;">Nenhum participante adicionado.</span>';
            return;
        }

        el('participantsList').innerHTML = selectedParticipants.map((p, i) => `
            <span class="events-chip">
                ${escapeHtml(p.name || p.email || 'Participante')}
                ${p.email ? `| ${escapeHtml(p.email)}` : ''}
                | ${escapeHtml(p.role || 'attendee')}
                <button type="button" data-remove-participant="${i}">×</button>
            </span>
        `).join('');
    }

    function renderReminders() {
        if (!selectedReminders.length) {
            el('remindersList').innerHTML = '<span style="color:#647878;font-size:.9rem;font-weight:600;">Nenhum lembrete configurado.</span>';
            return;
        }

        el('remindersList').innerHTML = selectedReminders.map((r) => `
            <span class="events-chip">
                ${r.minutesBefore} min antes
                <button type="button" data-remove-reminder="${selectedReminders.indexOf(r)}">×</button>
            </span>
        `).join('');
    }

    function setDefaultDates() {
        const start = new Date();
        start.setDate(start.getDate() + 1);
        start.setHours(14, 0, 0, 0);

        const end = new Date(start);
        end.setHours(15, 0, 0, 0);

        el('startAt').value = toLocalInput(start);
        el('endAt').value = toLocalInput(end);
    }

    async function syncChildren(eventId) {
        await Promise.all(
            selectedParticipants.map(async (p) => {
                const r = await api(`/api/events/${eventId}/participants`, {
                    method: 'POST',
                    body: JSON.stringify({
                        contactId: p.contactId || null,
                        name: p.name || null,
                        email: p.email || null,
                        role: p.role || 'attendee',
                    }),
                });

                if (p.responseStatus && p.responseStatus !== 'pending') {
                    await api(`/api/event-participants/${r.data.id}`, {
                        method: 'PATCH',
                        body: JSON.stringify({ responseStatus: p.responseStatus }),
                    });
                }
            })
        );

        await Promise.all(
            selectedReminders.map((r) =>
                api(`/api/events/${eventId}/reminders`, {
                    method: 'POST',
                    body: JSON.stringify({ type: 'notification', minutesBefore: Number(r.minutesBefore) }),
                })
            )
        );
    }

    async function init() {
        const urlParams = new URLSearchParams(window.location.search);
        const dateFromUrl = urlParams.get('date');
        const calendarIdFromUrl = urlParams.get('calendar_id');
        const suggestionIdFromUrl = urlParams.get('suggestionId');

        async function run() {
            await loadBootstrap();

            // default dates
            let start;
            if (dateFromUrl) {
                start = new Date(dateFromUrl + 'T14:00:00');
            } else {
                start = new Date();
                start.setDate(start.getDate() + 1);
                start.setHours(14, 0, 0, 0);
            }
            const end = new Date(start);
            end.setHours(start.getHours() + 1, 0, 0, 0);
            el('startAt').value = toLocalInput(start);
            el('endAt').value = toLocalInput(end);

            if (calendarIdFromUrl) el('calendarId').value = calendarIdFromUrl;

            renderParticipants();
            renderReminders();

            await loadEventSuggestions();

            if (suggestionIdFromUrl) {
                await useEventSuggestion(suggestionIdFromUrl);
            }
        }

        el('eventForm')?.addEventListener('submit', async (event) => {
            event.preventDefault();
            clearFeedback();
            el('saveEventBtn').disabled = true;

            try {
                const payload = {
                    calendarId: Number(el('calendarId').value) || null,
                    title: el('title').value,
                    description: nullable(el('description').value),
                    startAt: el('startAt').value,
                    endAt: el('endAt').value,
                    timezone: el('timezone').value,
                    location: nullable(el('location').value),
                    meetingURL: nullable(el('meetingURL').value),
                    priority: el('priority').value,
                    isAllDay: el('isAllDay').checked,
                    isRecurring: el('isRecurring').checked,
                    status: el('status').value || 'confirmed',
                };

                const response = await api('/api/events', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });

                await syncChildren(response.data.id);
                window.location.href = el('eventForm')?.dataset?.redirect || "";
            } catch (error) {
                showFeedback(error.message, 'error');
                el('saveEventBtn').disabled = false;
            }
        });

        el('showManualParticipantBtn')?.addEventListener('click', () => {
            el('manualParticipantFields').classList.toggle('is-hidden');
        });

        el('showContactParticipantBtn')?.addEventListener('click', () => {
            el('contactParticipantFields').classList.toggle('is-hidden');
        });

        el('addContactBtn')?.addEventListener('click', () => {
            const contact = contacts.find((c) => String(c.id) === el('contactSelect').value);
            if (!contact) return;

            selectedParticipants.push({
                contactId: contact.id,
                name: contact.name,
                email: contact.email,
                role: el('contactRole').value,
                responseStatus: el('contactResponseStatus').value,
            });

            renderParticipants();
            el('contactSelect').value = '';
        });

        el('addManualBtn')?.addEventListener('click', () => {
            const name = nullable(el('manualName').value);
            const email = nullable(el('manualEmail').value);
            if (!name && !email) return;

            selectedParticipants.push({
                contactId: null,
                name,
                email,
                role: el('manualRole').value,
                responseStatus: el('manualResponseStatus').value,
            });

            el('manualName').value = '';
            el('manualEmail').value = '';
            renderParticipants();
        });

        el('participantsList')?.addEventListener('click', (event) => {
            const btn = event.target.closest('[data-remove-participant]');
            if (!btn) return;
            selectedParticipants.splice(Number(btn.dataset.removeParticipant), 1);
            renderParticipants();
        });

        document.querySelectorAll('[data-reminder]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const minutes = Number(btn.dataset.reminder);
                if (!selectedReminders.some((r) => Number(r.minutesBefore) === minutes)) {
                    selectedReminders.push({ minutesBefore: minutes });
                    renderReminders();
                }
            });
        });

        el('remindersList')?.addEventListener('click', (event) => {
            const btn = event.target.closest('[data-remove-reminder]');
            if (!btn) return;
            selectedReminders.splice(Number(btn.dataset.removeReminder), 1);
            renderReminders();
        });

        el('eventSuggestionsList')?.addEventListener('click', (event) => {
            const btn = event.target.closest('[data-action="use-suggestion"]');
            if (!btn) return;
            useEventSuggestion(btn.dataset.suggestionId).catch((error) => showFeedback(error.message, 'error'));
        });

        el('refreshSuggestionsBtn')?.addEventListener('click', () => {
            loadEventSuggestions().catch((error) => {
                el('eventSuggestionsList').innerHTML = `<div class="events-empty">${escapeHtml(error.message)}</div>`;
            });
        });

        // redirect fixed like old inline
        // manter o comportamento do template original
        window.__EVENTS_CREATE_REDIRECT__ = "{{ route('events.index') }}";

        // submit handler fix (replace above redirect):
        el('eventForm')?.addEventListener('submit', () => {
            // não-op (apenas garante que existe)
        }, { once: true });

        run().catch((error) => showFeedback(error.message, 'error'));
    }

    // patch redirect with template value
    try {
        init();
    } catch (e) {
        // ignore
    }
})();

