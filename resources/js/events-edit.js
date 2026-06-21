(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const eventPage = document.querySelector('.events-page');
    if (!eventPage) return;
    const eventId = parseInt(eventPage.dataset.eventId, 10);

    let currentEvent = null;
    let calendars = [];
    let contacts = [];
    let selectedParticipants = [];
    let selectedReminders = [];

    const el = (id) => document.getElementById(id);
    const nullable = (value) => (value ?? '').trim() || null;

    const escapeHtml = (value) => String(value ?? '').replace(/&/g, '&amp;').replace(/</g, '<').replace(/>/g, '>').replace(/"/g, '"');
    const pad = (n) => String(n).padStart(2, '0');

    function toLocalInput(value) {
        if (!value) return '';
        const d = new Date(value);
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }

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

        if (!response.ok) throw new Error(payload.message || 'Não foi possível concluir a operação.');

        return payload;
    };

    function showFeedback(message, type = 'success') {
        const ff = el('formFeedback');
        if (!ff) return;
        ff.textContent = message;
        ff.className = `events-feedback is-visible ${type === 'error' ? 'is-error' : ''}`;
    }

    function clearFeedback() {
        const ff = el('formFeedback');
        if (!ff) return;
        ff.textContent = '';
        ff.className = 'events-feedback';
    }

    async function loadBootstrap() {
        const [calendarResponse, contactResponse] = await Promise.all([
            api('/api/calendars'),
            api('/api/contacts?perPage=100'),
        ]);

        calendars = calendarResponse.data || [];
        contacts = contactResponse.data || [];

        const contactSelect = el('contactSelect');
        if (contactSelect) {
            contactSelect.innerHTML = '<option value="">Selecionar contato...</option>' + contacts
                .map(c => `<option value="${c.id}">${escapeHtml(c.name)}${c.email ? ` - ${escapeHtml(c.email)}` : ''}</option>`)
                .join('');
        }
    }

    function renderCalendarSelect(selectedId) {
        const calendarSelect = el('calendarId');
        if (!calendarSelect) return;
        calendarSelect.innerHTML = calendars
            .filter((c) => c.isActive)
            .map(
                (c) => `<option value="${c.id}" ${String(c.id) === String(selectedId) ? 'selected' : ''}>${escapeHtml(c.name)}</option>`
            )
            .join('');
    }

    async function loadEvent() {
        const response = await api(`/api/events/${eventId}`);
        currentEvent = response.data;
        fillForm(currentEvent);

        const pageLoader = el('pageLoader');
        if (pageLoader) pageLoader.classList.add('is-hidden');

        const eventForm = el('eventForm');
        if (eventForm) eventForm.classList.remove('is-hidden');

        const eventSubtitle = el('eventSubtitle');
        if (eventSubtitle) eventSubtitle.textContent = currentEvent.title || 'Evento selecionado';
    }

    function fillForm(event) {
        el('title').value = event.title || '';
        el('description').value = event.description || '';
        el('location').value = event.location || '';
        el('meetingURL').value = event.meetingURL || '';

        el('startAt').value = toLocalInput(event.startAt);
        el('endAt').value = toLocalInput(event.endAt);
        el('timezone').value = event.timezone || 'America/Sao_Paulo';

        el('isAllDay').checked = !!event.isAllDay;
        el('isRecurring').checked = !!event.isRecurring;
        el('status').value = event.status || 'confirmed';
        el('priority').value = event.priority || 'medium';

        renderCalendarSelect(event.calendarId);

        selectedParticipants = (event.participants || []).map((p) => ({
            existingId: p.id,
            contactId: p.contactId || null,
            name: p.name || null,
            email: p.email || null,
            role: p.role || 'attendee',
            responseStatus: p.responseStatus || 'pending',
        }));

        selectedReminders = (event.reminders || []).map((r) => ({
            existingId: r.id,
            minutesBefore: r.minutesBefore,
        }));

        renderParticipants();
        renderReminders();
    }

    function renderParticipants() {
        const participantsList = el('participantsList');
        if (!participantsList) return;

        if (!selectedParticipants.length) {
            participantsList.innerHTML = '<span style="color:#647878;font-size:.9rem;font-weight:600;">Nenhum participante adicionado.</span>';
            return;
        }

        participantsList.innerHTML = selectedParticipants
            .map(
                (p, i) => `
                <span class="events-chip">
                    ${escapeHtml(p.name || p.email || 'Participante')}
                    ${p.email ? `| ${escapeHtml(p.email)}` : ''}
                    | ${escapeHtml(p.role || 'attendee')}
                    <button type="button" data-remove-participant="${i}">×</button>
                </span>
            `
            )
            .join('');
    }

    function renderReminders() {
        const remindersList = el('remindersList');
        if (!remindersList) return;

        if (!selectedReminders.length) {
            remindersList.innerHTML = '<span style="color:#647878;font-size:.9rem;font-weight:600;">Nenhum lembrete configurado.</span>';
            return;
        }

        remindersList.innerHTML = selectedReminders
            .map(
                (r, i) => `
                <span class="events-chip">
                    ${r.minutesBefore} min antes
                    <button type="button" data-remove-reminder="${i}">×</button>
                </span>
            `
            )
            .join('');
    }

    async function syncChildren() {
        const savedParticipantIds = (currentEvent.participants || []).map((p) => p.id);
        const keptParticipantIds = selectedParticipants.filter((p) => p.existingId).map((p) => p.existingId);
        const removedParticipantIds = savedParticipantIds.filter((id) => !keptParticipantIds.includes(id));

        await Promise.all(removedParticipantIds.map((id) => api(`/api/event-participants/${id}`, { method: 'DELETE' })));

        await Promise.all(
            selectedParticipants
                .filter((p) => !p.existingId)
                .map(async (p) => {
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

        const savedReminderIds = (currentEvent.reminders || []).map((r) => r.id);
        const keptReminderIds = selectedReminders.filter((r) => r.existingId).map((r) => r.existingId);
        const removedReminderIds = savedReminderIds.filter((id) => !keptReminderIds.includes(id));

        await Promise.all(removedReminderIds.map((id) => api(`/api/event-reminders/${id}`, { method: 'DELETE' })));

        await Promise.all(
            selectedReminders
                .filter((r) => !r.existingId)
                .map((r) =>
                    api(`/api/events/${eventId}/reminders`, {
                        method: 'POST',
                        body: JSON.stringify({ type: 'notification', minutesBefore: Number(r.minutesBefore) }),
                    })
                )
        );
    }

    function bindUi() {
        const eventForm = el('eventForm');
        const deleteEventBtn = el('deleteEventBtn');

        if (eventForm) {
            eventForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                clearFeedback();
                const saveBtn = el('saveEventBtn');
                if (saveBtn) saveBtn.disabled = true;

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

                    await api(`/api/events/${eventId}`, { method: 'PATCH', body: JSON.stringify(payload) });
                    await syncChildren();

                    const refreshed = await api(`/api/events/${eventId}`);
                    currentEvent = refreshed.data;
                    fillForm(currentEvent);

                    showFeedback('Evento atualizado com sucesso.');
                } catch (error) {
                    showFeedback(error.message, 'error');
                } finally {
                    if (saveBtn) saveBtn.disabled = false;
                }
            });
        }

        if (deleteEventBtn) {
            deleteEventBtn.addEventListener('click', async () => {
                if (!confirm('Excluir este evento? Participantes e lembretes vinculados também serão removidos.')) return;
                deleteEventBtn.disabled = true;
                try {
                    await api(`/api/events/${eventId}`, { method: 'DELETE' });
                    window.location.href = eventPage.dataset.redirect || '/events';
                } catch (error) {
                    showFeedback(error.message, 'error');
                    deleteEventBtn.disabled = false;
                }
            });
        }

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
    }

    async function init() {
        bindUi();
        await loadBootstrap();
        await loadEvent();
    }

    init().catch((e) => {
        const pageLoader = el('pageLoader');
        if (pageLoader) pageLoader.textContent = `Erro ao carregar evento: ${e.message}`;
    });
})();

