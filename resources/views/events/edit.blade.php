<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        <style>
            .events-page { max-width: 900px; margin: 0 auto; padding: 24px; }

            .events-card {
                background: #fff;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                box-shadow: 0 14px 35px rgba(13,43,43,.06);
            }

            .events-card__header { padding: 18px; border-bottom: 1px solid #dbe7e7; }
            .events-card__body { padding: 18px; }

            .events-eyebrow { color: #008f91; font-size: .72rem; font-weight: 900; letter-spacing: .18em; text-transform: uppercase; }
            .events-title { margin-top: 4px; color: #0d2b2b; font-size: 1.35rem; font-weight: 900; }
            .events-heading { color: #0d2b2b; font-size: 1.8rem; font-weight: 900; }
            .events-muted { color: #647878; font-size: .9rem; font-weight: 600; }

            .events-top { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }

            .events-search,
            .events-field input,
            .events-field textarea,
            .events-field select {
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

            .events-search:focus,
            .events-field input:focus,
            .events-field textarea:focus,
            .events-field select:focus {
                border-color: #008f91;
                box-shadow: 0 0 0 3px rgba(0,143,145,.12);
            }

            .events-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 14px; }

            .events-field { display: flex; flex-direction: column; gap: 6px; }

            .events-field label {
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .events-field textarea { min-height: 92px; resize: vertical; }
            .events-field--full { grid-column: 1 / -1; }

            .events-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

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

            .events-btn:hover { transform: translate(-1px,-1px); box-shadow: 3px 3px 0 #0d2b2b; }
            .events-btn:disabled { cursor: not-allowed; opacity: .55; transform: none; box-shadow: none; }

            .events-btn--primary { background: #008f91; color: #fff; box-shadow: 3px 3px 0 #0d2b2b; }
            .events-btn--ghost { background: #fff; color: #0d2b2b; }
            .events-btn--soft { background: #e5ffff; color: #006b6d; border-color: #008f91; }
            .events-btn--danger { background: #fff0f0; border-color: #c0392b; color: #c0392b; }

            .events-panel { border: 1px solid #dbe7e7; border-radius: 8px; background: #fafdff; padding: 14px; }
            .events-section-title { margin-bottom: 12px; color: #0d2b2b; font-size: 1rem; font-weight: 900; }

            .events-chip-list { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }

            .events-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                max-width: 100%;
                border-radius: 999px;
                border: 1px solid #cfe0e0;
                background: #fff;
                color: #0d2b2b;
                padding: 6px 10px;
                font-size: .82rem;
                font-weight: 800;
            }

            .events-chip button { color: #c0392b; font-weight: 900; }

            .events-option-list { display: grid; gap: 8px; }

            .events-option-chip {
                width: fit-content;
                min-height: 28px;
                cursor: pointer;
                border: 0;
                border-radius: 0;
                background: transparent;
                padding: 0;
                color: #0d2b2b;
                font-size: .82rem;
                font-weight: 800;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .events-option-chip input[type="checkbox"] {
                appearance: auto;
                flex: 0 0 16px;
                width: 16px;
                height: 16px;
                margin: 0;
                cursor: pointer;
                accent-color: #008f91;
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
            }

            .events-feedback.is-visible { display: block; }
            .events-feedback.is-error { border-color: #f3b4b4; background: #fff0f0; color: #a32222; }
            .is-hidden { display: none !important; }

            @media (max-width: 700px) {
                .events-grid { grid-template-columns: 1fr; }
            }
        </style>

        <div class="events-page" data-event-id="{{ (int) $eventId }}">
            <div class="events-top">
                <div>
                    <h1 class="events-heading">Editar evento</h1>
                    <p class="events-muted" id="eventSubtitle">Carregando...</p>
                </div>
                <a href="{{ route('events.index') }}" class="events-btn events-btn--ghost">← Voltar</a>
            </div>

            <section class="events-card">
                <div class="events-card__header">
                    <p class="events-eyebrow">Evento</p>
                    <h2 class="events-title">Informações do evento</h2>
                </div>

                <div class="events-card__body">
                    <div id="pageLoader" class="events-empty">Carregando dados do evento...</div>

                    <form id="eventForm" class="space-y-5 is-hidden">
                        <div class="events-panel">
                            <h3 class="events-section-title">Informações principais</h3>
                            <div class="events-grid">
                                <div class="events-field events-field--full">
                                    <label for="title">Título</label>
                                    <input id="title" maxlength="200" required type="text">
                                </div>
                                <div class="events-field events-field--full">
                                    <label for="description">Descrição</label>
                                    <textarea id="description" maxlength="2000"></textarea>
                                </div>
                                <div class="events-field">
                                    <label for="location">Local</label>
                                    <input id="location" maxlength="500" type="text">
                                </div>
                                <div class="events-field">
                                    <label for="meetingURL">URL da reunião</label>
                                    <input id="meetingURL" maxlength="1000" type="url">
                                </div>
                                <div class="events-field">
                                    <label for="calendarId">Calendário</label>
                                    <select id="calendarId"></select>
                                </div>
                            </div>
                        </div>

                        <div class="events-panel">
                            <h3 class="events-section-title">Data e horário</h3>
                            <div class="events-grid">
                                <div class="events-field">
                                    <label for="startAt">Início</label>
                                    <input id="startAt" required type="datetime-local">
                                </div>
                                <div class="events-field">
                                    <label for="endAt">Fim</label>
                                    <input id="endAt" required type="datetime-local">
                                </div>
                                <div class="events-field">
                                    <label for="timezone">Fuso horário</label>
                                    <select id="timezone">
                                        <option value="America/Sao_Paulo">America/Sao_Paulo</option>
                                        <option value="UTC">UTC</option>
                                    </select>
                                </div>
                                <div class="events-field">
                                    <label>Opções</label>
                                    <div class="events-option-list">
                                        <label class="events-option-chip">
                                            <input id="isAllDay" type="checkbox">
                                            <span>Dia todo</span>
                                        </label>
                                        <label class="events-option-chip">
                                            <input id="isRecurring" type="checkbox">
                                            <span>Recorrente</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="events-panel">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="events-eyebrow">Participantes</p>
                                    <p class="events-muted mt-1">Adicione manualmente ou selecione um contato.</p>
                                </div>
                                <div class="events-actions">
                                    <button id="showManualParticipantBtn" class="events-btn events-btn--ghost" type="button">+ Participante</button>
                                    <button id="showContactParticipantBtn" class="events-btn events-btn--soft" type="button">👤 Contato</button>
                                </div>
                            </div>

                            <div id="contactParticipantFields" class="mt-4 is-hidden">
                                <div class="grid gap-3 md:grid-cols-[1fr_140px_150px_auto]">
                                    <select id="contactSelect" class="events-search"></select>
                                    <select id="contactRole" class="events-search">
                                        <option value="attendee">Participante</option>
                                        <option value="organizer">Organizador</option>
                                    </select>
                                    <select id="contactResponseStatus" class="events-search">
                                        <option value="pending">Pendente</option>
                                        <option value="accepted">Aceito</option>
                                        <option value="declined">Recusado</option>
                                        <option value="tentative">Talvez</option>
                                    </select>
                                    <button id="addContactBtn" class="events-btn events-btn--ghost" type="button">Adicionar</button>
                                </div>
                            </div>

                            <div id="manualParticipantFields" class="mt-4 is-hidden">
                                <div class="grid gap-3 md:grid-cols-[1fr_1fr_140px_150px_auto]">
                                    <input id="manualName" class="events-search" type="text" placeholder="Nome">
                                    <input id="manualEmail" class="events-search" type="email" placeholder="email@exemplo.com">
                                    <select id="manualRole" class="events-search">
                                        <option value="attendee">Participante</option>
                                        <option value="organizer">Organizador</option>
                                    </select>
                                    <select id="manualResponseStatus" class="events-search">
                                        <option value="pending">Pendente</option>
                                        <option value="accepted">Aceito</option>
                                        <option value="declined">Recusado</option>
                                        <option value="tentative">Talvez</option>
                                    </select>
                                    <button id="addManualBtn" class="events-btn events-btn--ghost" type="button">Adicionar</button>
                                </div>
                            </div>

                            <div id="participantsList" class="events-chip-list"></div>
                        </div>

                        <div class="events-panel">
                            <p class="events-eyebrow">Lembretes</p>
                            <div class="mt-3 events-actions">
                                <button class="events-btn events-btn--soft" type="button" data-reminder="10">10 min</button>
                                <button class="events-btn events-btn--soft" type="button" data-reminder="15">15 min</button>
                                <button class="events-btn events-btn--soft" type="button" data-reminder="30">30 min</button>
                                <button class="events-btn events-btn--soft" type="button" data-reminder="60">1 hora</button>
                                <button class="events-btn events-btn--soft" type="button" data-reminder="1440">1 dia</button>
                            </div>
                            <div id="remindersList" class="events-chip-list"></div>
                        </div>

                        <div class="events-panel">
                            <h3 class="events-section-title">Configurações</h3>
                            <div class="events-grid">
                                <div class="events-field">
                                    <label for="status">Status</label>
                                    <select id="status">
                                        <option value="draft">Rascunho</option>
                                        <option value="confirmed">Confirmado</option>
                                        <option value="cancelled">Cancelado</option>
                                    </select>
                                </div>
                                <div class="events-field">
                                    <label for="priority">Prioridade</label>
                                    <select id="priority">
                                        <option value="low">Baixa</option>
                                        <option value="medium">Média</option>
                                        <option value="high">Alta</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="formFeedback" class="events-feedback" role="status"></div>

                        <div class="events-actions">
                            <button id="deleteEventBtn" class="events-btn events-btn--danger" type="button">Excluir evento</button>
                            <span style="flex:1;"></span>
                            <a href="{{ route('events.index') }}" class="events-btn events-btn--ghost">Cancelar</a>
                            <button id="saveEventBtn" class="events-btn events-btn--primary" type="submit">Salvar alterações</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const eventId = parseInt(document.querySelector('.events-page').dataset.eventId, 10);

            let currentEvent = null;
            let calendars = [];
            let contacts = [];
            let selectedParticipants = [];
            let selectedReminders = [];

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
            const nullable = value => value.trim() || null;
            const escapeHtml = value => String(value ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            const pad = n => String(n).padStart(2, '0');

            function toLocalInput(value) {
                if (!value) return '';
                const d = new Date(value);
                return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
            }

            function showFeedback(message, type = 'success') {
                el('formFeedback').textContent = message;
                el('formFeedback').className = `events-feedback is-visible ${type === 'error' ? 'is-error' : ''}`;
            }

            function clearFeedback() {
                el('formFeedback').textContent = '';
                el('formFeedback').className = 'events-feedback';
            }

            async function loadBootstrap() {
                const [calendarResponse, contactResponse] = await Promise.all([
                    api('/api/calendars'),
                    api('/api/contacts?perPage=100'),
                ]);

                calendars = calendarResponse.data || [];
                contacts = contactResponse.data || [];

                el('contactSelect').innerHTML = '<option value="">Selecionar contato...</option>' + contacts
                    .map(c => `<option value="${c.id}">${escapeHtml(c.name)}${c.email ? ` - ${escapeHtml(c.email)}` : ''}</option>`)
                    .join('');
            }

            function renderCalendarSelect(selectedId) {
                el('calendarId').innerHTML = calendars
                    .filter(c => c.isActive)
                    .map(c => `<option value="${c.id}" ${String(c.id) === String(selectedId) ? 'selected' : ''}>${escapeHtml(c.name)}</option>`)
                    .join('');
            }

            async function loadEvent() {
                const response = await api(`/api/events/${eventId}`);
                currentEvent = response.data;
                fillForm(currentEvent);
                el('pageLoader').classList.add('is-hidden');
                el('eventForm').classList.remove('is-hidden');
                el('eventSubtitle').textContent = currentEvent.title || 'Evento selecionado';
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

                selectedParticipants = (event.participants || []).map(p => ({
                    existingId: p.id,
                    contactId: p.contactId || null,
                    name: p.name || null,
                    email: p.email || null,
                    role: p.role || 'attendee',
                    responseStatus: p.responseStatus || 'pending',
                }));

                selectedReminders = (event.reminders || []).map(r => ({
                    existingId: r.id,
                    minutesBefore: r.minutesBefore,
                }));

                renderParticipants();
                renderReminders();
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
                el('remindersList').innerHTML = selectedReminders.map((r, i) => `
                    <span class="events-chip">
                        ${r.minutesBefore} min antes
                        <button type="button" data-remove-reminder="${i}">×</button>
                    </span>
                `).join('');
            }

            async function syncChildren() {
                const savedParticipantIds = (currentEvent.participants || []).map(p => p.id);
                const keptParticipantIds = selectedParticipants.filter(p => p.existingId).map(p => p.existingId);
                const removedParticipantIds = savedParticipantIds.filter(id => !keptParticipantIds.includes(id));

                await Promise.all(removedParticipantIds.map(id => api(`/api/event-participants/${id}`, { method: 'DELETE' })));

                await Promise.all(selectedParticipants.filter(p => !p.existingId).map(async p => {
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
                }));

                const savedReminderIds = (currentEvent.reminders || []).map(r => r.id);
                const keptReminderIds = selectedReminders.filter(r => r.existingId).map(r => r.existingId);
                const removedReminderIds = savedReminderIds.filter(id => !keptReminderIds.includes(id));

                await Promise.all(removedReminderIds.map(id => api(`/api/event-reminders/${id}`, { method: 'DELETE' })));

                await Promise.all(selectedReminders.filter(r => !r.existingId).map(r =>
                    api(`/api/events/${eventId}/reminders`, {
                        method: 'POST',
                        body: JSON.stringify({ type: 'notification', minutesBefore: Number(r.minutesBefore) }),
                    })
                ));
            }

            el('eventForm').addEventListener('submit', async event => {
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

                    await api(`/api/events/${eventId}`, {
                        method: 'PATCH',
                        body: JSON.stringify(payload),
                    });

                    await syncChildren();

                    // Recarrega o evento para sincronizar IDs de participantes/lembretes criados
                    const refreshed = await api(`/api/events/${eventId}`);
                    currentEvent = refreshed.data;
                    fillForm(currentEvent);

                    showFeedback('Evento atualizado com sucesso.');
                } catch (error) {
                    showFeedback(error.message, 'error');
                } finally {
                    el('saveEventBtn').disabled = false;
                }
            });

            el('deleteEventBtn').addEventListener('click', async () => {
                if (!confirm('Excluir este evento? Participantes e lembretes vinculados também serão removidos.')) return;

                el('deleteEventBtn').disabled = true;

                try {
                    await api(`/api/events/${eventId}`, { method: 'DELETE' });
                    window.location.href = "{{ route('events.index') }}";
                } catch (error) {
                    showFeedback(error.message, 'error');
                    el('deleteEventBtn').disabled = false;
                }
            });

            el('showManualParticipantBtn').addEventListener('click', () => {
                el('manualParticipantFields').classList.toggle('is-hidden');
            });

            el('showContactParticipantBtn').addEventListener('click', () => {
                el('contactParticipantFields').classList.toggle('is-hidden');
            });

            el('addContactBtn').addEventListener('click', () => {
                const contact = contacts.find(c => String(c.id) === el('contactSelect').value);
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

            el('addManualBtn').addEventListener('click', () => {
                const name = nullable(el('manualName').value);
                const email = nullable(el('manualEmail').value);
                if (!name && !email) return;
                selectedParticipants.push({ contactId: null, name, email, role: el('manualRole').value, responseStatus: el('manualResponseStatus').value });
                el('manualName').value = '';
                el('manualEmail').value = '';
                renderParticipants();
            });

            el('participantsList').addEventListener('click', event => {
                const btn = event.target.closest('[data-remove-participant]');
                if (!btn) return;
                selectedParticipants.splice(Number(btn.dataset.removeParticipant), 1);
                renderParticipants();
            });

            document.querySelectorAll('[data-reminder]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const minutes = Number(btn.dataset.reminder);
                    if (!selectedReminders.some(r => Number(r.minutesBefore) === minutes)) {
                        selectedReminders.push({ minutesBefore: minutes });
                        renderReminders();
                    }
                });
            });

            el('remindersList').addEventListener('click', event => {
                const btn = event.target.closest('[data-remove-reminder]');
                if (!btn) return;
                selectedReminders.splice(Number(btn.dataset.removeReminder), 1);
                renderReminders();
            });

            loadBootstrap()
                .then(() => loadEvent())
                .catch(error => {
                    el('pageLoader').textContent = `Erro ao carregar evento: ${error.message}`;
                });
        </script>
    </div>
</x-app-layout>
