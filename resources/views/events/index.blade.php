<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Eventos</h2>
                <p class="mt-1 text-sm text-gray-500">Organize seus compromissos e acompanhe tudo em um só lugar.</p>
            </div>
            <span id="last-status" class="inline-flex w-fit rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">Pronto</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 xl:grid-cols-[minmax(420px,560px)_1fr]">
                <section class="space-y-6">
                    <div class="rounded bg-white p-5 shadow">
                        <div>
                            <p class="text-xs font-semibold uppercase text-blue-700">Novo compromisso</p>
                            <h3 class="mt-1 text-lg font-semibold text-gray-900">Criar evento</h3>
                        </div>

                        <form id="create-form" class="mt-5 space-y-4">
                            <div>
                                <label for="create-title" class="block text-sm font-medium text-gray-700">Título</label>
                                <input id="create-title" type="text" maxlength="200" required placeholder="Ex.: Reunião de planejamento" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="create-description" class="block text-sm font-medium text-gray-700">Descrição</label>
                                <textarea id="create-description" rows="3" maxlength="2000" placeholder="Inclua detalhes importantes para lembrar depois." class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="create-start" class="block text-sm font-medium text-gray-700">Início</label>
                                    <input id="create-start" type="datetime-local" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="create-end" class="block text-sm font-medium text-gray-700">Fim</label>
                                    <input id="create-end" type="datetime-local" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="create-priority" class="block text-sm font-medium text-gray-700">Prioridade</label>
                                    <select id="create-priority" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="low">Baixa</option>
                                        <option value="medium" selected>Média</option>
                                        <option value="high">Alta</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="create-location" class="block text-sm font-medium text-gray-700">Local</label>
                                    <input id="create-location" type="text" maxlength="500" placeholder="Sala, endereço ou local online" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label for="create-url" class="block text-sm font-medium text-gray-700">Link da reunião</label>
                                <input id="create-url" type="url" maxlength="1000" placeholder="https://meet.example.com/reuniao" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <label class="flex items-center gap-3 rounded border border-gray-200 px-3 py-2 text-sm text-gray-700">
                                <input id="create-all-day" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                Evento de dia inteiro
                            </label>

                            <button type="submit" class="rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800">
                                Salvar evento
                            </button>
                        </form>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <h3 class="text-base font-semibold text-gray-900">Editar evento selecionado</h3>
                        <form id="update-form" class="mt-4 space-y-4">
                            <div>
                                <label for="event-id" class="block text-sm font-medium text-gray-700">Evento</label>
                                <select id="event-id" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="">Carregando eventos...</option>
                                </select>
                            </div>

                            <div>
                                <label for="update-title" class="block text-sm font-medium text-gray-700">Título</label>
                                <input id="update-title" type="text" maxlength="200" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="update-description" class="block text-sm font-medium text-gray-700">Descrição</label>
                                <textarea id="update-description" rows="3" maxlength="2000" class="mt-1 block w-full rounded border-gray-300 shadow-sm"></textarea>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="update-start" class="block text-sm font-medium text-gray-700">Início</label>
                                    <input id="update-start" type="datetime-local" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="update-end" class="block text-sm font-medium text-gray-700">Fim</label>
                                    <input id="update-end" type="datetime-local" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label for="update-calendar" class="block text-sm font-medium text-gray-700">Calendário</label>
                                    <select id="update-calendar" class="mt-1 block w-full rounded border-gray-300 shadow-sm"></select>
                                </div>
                                <div>
                                    <label for="update-status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select id="update-status" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                        <option value="draft">Rascunho</option>
                                        <option value="confirmed">Confirmado</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="update-priority" class="block text-sm font-medium text-gray-700">Prioridade</label>
                                    <select id="update-priority" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                        <option value="low">Baixa</option>
                                        <option value="medium">Média</option>
                                        <option value="high">Alta</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Salvar alterações</button>
                                <button id="cancel-button" type="button" class="rounded bg-amber-600 px-4 py-2 text-sm font-semibold text-white">Cancelar evento</button>
                                <button id="delete-button" type="button" class="rounded bg-red-600 px-4 py-2 text-sm font-semibold text-white">Excluir evento</button>
                            </div>
                        </form>
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase text-blue-700">Consulta</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Minha agenda</h3>
                            </div>
                            <button id="list-button" type="button" class="rounded bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Atualizar</button>
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="filter-calendar" class="block text-sm font-medium text-gray-700">Calendário</label>
                                <select id="filter-calendar" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="">Todos</option>
                                </select>
                            </div>
                            <div>
                                <label for="filter-status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="filter-status" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="">Todos</option>
                                    <option value="draft">Rascunho</option>
                                    <option value="confirmed">Confirmado</option>
                                    <option value="cancelled">Cancelado</option>
                                </select>
                            </div>
                            <div>
                                <label for="filter-from" class="block text-sm font-medium text-gray-700">De</label>
                                <input id="filter-from" type="datetime-local" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="filter-to" class="block text-sm font-medium text-gray-700">Até</label>
                                <input id="filter-to" type="datetime-local" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div id="event-list" class="space-y-4"></div>
                </section>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const lastStatus = document.getElementById('last-status');
        const eventSelect = document.getElementById('event-id');
        const eventList = document.getElementById('event-list');
        let events = [];

        const field = (id) => document.getElementById(id);
        const nullable = (value) => value.trim() || null;
        const priorityLabels = { low: 'Baixa', medium: 'Média', high: 'Alta' };
        const statusLabels = { draft: 'Rascunho', confirmed: 'Confirmado', cancelled: 'Cancelado' };

        function localDateTime(date) {
            const offset = date.getTimezoneOffset() * 60000;
            return new Date(date.getTime() - offset).toISOString().slice(0, 16);
        }

        function formatDate(value) {
            return value ? new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeStyle: 'short' }).format(new Date(value)) : 'Sem data';
        }

        function initializeDates() {
            const start = new Date();
            start.setDate(start.getDate() + 1);
            start.setHours(14, 0, 0, 0);
            const end = new Date(start);
            end.setHours(15, 0, 0, 0);
            field('create-start').value = localDateTime(start);
            field('create-end').value = localDateTime(end);
        }

        function createPayload() {
            return {
                title: field('create-title').value,
                description: nullable(field('create-description').value),
                startAt: field('create-start').value,
                endAt: field('create-end').value,
                location: nullable(field('create-location').value),
                meetingURL: nullable(field('create-url').value),
                priority: field('create-priority').value,
                isAllDay: field('create-all-day').checked,
            };
        }

        function updatePayload() {
            return {
                calendarId: Number(field('update-calendar').value),
                title: field('update-title').value,
                description: nullable(field('update-description').value),
                startAt: field('update-start').value,
                endAt: field('update-end').value,
                status: field('update-status').value,
                priority: field('update-priority').value,
            };
        }

        function setStatus(text, ok = null) {
            lastStatus.textContent = text;
            lastStatus.className = 'inline-flex w-fit rounded px-3 py-1 text-xs font-medium';
            lastStatus.classList.add(
                ok === true ? 'bg-green-100' : ok === false ? 'bg-red-100' : 'bg-gray-100',
                ok === true ? 'text-green-800' : ok === false ? 'text-red-800' : 'text-gray-700',
            );
        }

        function selectedId() {
            if (!eventSelect.value) throw new Error('Selecione um evento.');
            return eventSelect.value;
        }

        function fillCalendarSelect(select, calendars, includeAll = false) {
            const current = select.value;
            select.innerHTML = '';
            if (includeAll) select.add(new Option('Todos', ''));
            calendars.forEach((calendar) => {
                if (calendar.isActive) select.add(new Option(calendar.name, calendar.id));
            });
            if ([...select.options].some((option) => option.value === current)) select.value = current;
        }

        async function loadCalendars() {
            const response = await fetch('/api/calendars', { headers: { Accept: 'application/json' } });
            const data = await response.json();
            if (!response.ok) throw new Error(data?.message || 'Não foi possível carregar os calendários.');
            const calendars = data.data ?? [];
            fillCalendarSelect(field('update-calendar'), calendars);
            fillCalendarSelect(field('filter-calendar'), calendars, true);
        }

        function listUrl() {
            const params = new URLSearchParams();
            const filters = {
                calendarId: field('filter-calendar').value,
                status: field('filter-status').value,
                from: field('filter-from').value,
                to: field('filter-to').value,
                perPage: '100',
            };
            Object.entries(filters).forEach(([key, value]) => {
                if (value) params.set(key, value);
            });
            return `/api/events?${params.toString()}`;
        }

        function renderEvents(items, preferredId = null) {
            events = items;
            const current = preferredId ? String(preferredId) : eventSelect.value;
            eventSelect.innerHTML = '';

            if (!items.length) {
                eventSelect.add(new Option('Nenhum evento encontrado', ''));
                eventSelect.disabled = true;
                eventList.innerHTML = '<div class="rounded bg-white p-8 text-center text-sm text-gray-600 shadow">Nenhum evento encontrado para os filtros atuais.</div>';
                fillUpdateForm();
                return;
            }

            eventSelect.disabled = false;
            eventSelect.add(new Option('Selecione um evento', ''));
            items.forEach((event) => eventSelect.add(new Option(`${event.title} - ${statusLabels[event.status] ?? event.status}`, event.id)));
            if ([...eventSelect.options].some((option) => option.value === current)) eventSelect.value = current;

            eventList.innerHTML = items.map((event) => `
                <article class="rounded bg-white p-5 shadow">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">${escapeHtml(event.title)}</h4>
                            <p class="mt-1 text-sm text-gray-600">${formatDate(event.startAt)} até ${formatDate(event.endAt)}</p>
                            ${event.description ? `<p class="mt-3 text-sm leading-6 text-gray-700">${escapeHtml(event.description)}</p>` : ''}
                            <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold">
                                <span class="rounded bg-blue-50 px-2.5 py-1 text-blue-800">${statusLabels[event.status] ?? event.status}</span>
                                <span class="rounded bg-gray-100 px-2.5 py-1 text-gray-700">Prioridade ${priorityLabels[event.priority] ?? event.priority}</span>
                                ${event.location ? `<span class="rounded bg-gray-100 px-2.5 py-1 text-gray-700">${escapeHtml(event.location)}</span>` : ''}
                            </div>
                        </div>
                        <button type="button" class="rounded bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300" onclick="selectEvent(${event.id})">Selecionar</button>
                    </div>
                </article>
            `).join('');
            fillUpdateForm();
        }

        function selectEvent(id) {
            eventSelect.value = String(id);
            fillUpdateForm();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function fillUpdateForm() {
            const event = events.find((item) => String(item.id) === eventSelect.value);
            field('update-title').value = event?.title ?? '';
            field('update-description').value = event?.description ?? '';
            field('update-start').value = event?.startAt ? localDateTime(new Date(event.startAt)) : '';
            field('update-end').value = event?.endAt ? localDateTime(new Date(event.endAt)) : '';
            field('update-calendar').value = event?.calendarId ? String(event.calendarId) : '';
            field('update-status').value = event?.status === 'cancelled' ? 'confirmed' : (event?.status ?? 'confirmed');
            field('update-priority').value = event?.priority ?? 'medium';
        }

        async function request(method, url, body = null, successMessage = 'Ação concluída.') {
            setStatus('Salvando...');
            const response = await fetch(url, {
                method,
                headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: body ? JSON.stringify(body) : null,
            });
            const text = await response.text();
            let data = null;
            try { data = text ? JSON.parse(text) : null; } catch { data = text; }
            if (!response.ok) throw new Error(data?.message || 'Não foi possível concluir a ação.');
            setStatus(successMessage, true);
            return { response, data };
        }

        async function loadEvents(preferredId = null) {
            const result = await request('GET', listUrl(), null, 'Agenda atualizada');
            renderEvents(result.data?.data ?? [], preferredId);
        }

        async function run(callback) {
            try { await callback(); } catch (error) { setStatus(error.message, false); }
        }

        function escapeHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        field('create-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(async () => {
                const result = await request('POST', '/api/events', createPayload(), 'Evento criado');
                field('create-form').reset();
                initializeDates();
                await loadEvents(result.data?.data?.id);
            });
        });

        field('update-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(async () => {
                const id = selectedId();
                await request('PATCH', `/api/events/${id}`, updatePayload(), 'Evento atualizado');
                await loadEvents(id);
            });
        });

        field('list-button').addEventListener('click', () => run(() => loadEvents()));
        field('cancel-button').addEventListener('click', () => run(async () => {
            const id = selectedId();
            if (!confirm('Cancelar este evento? Ele continuará no histórico como cancelado.')) return;
            await request('POST', `/api/events/${id}/cancel`, null, 'Evento cancelado');
            await loadEvents(id);
        }));
        field('delete-button').addEventListener('click', () => run(async () => {
            const id = selectedId();
            if (!confirm('Excluir este evento? Esta ação não pode ser desfeita.')) return;
            await request('DELETE', `/api/events/${id}`, null, 'Evento excluído');
            await loadEvents();
        }));
        eventSelect.addEventListener('change', fillUpdateForm);

        initializeDates();
        run(async () => {
            await loadCalendars();
            await loadEvents();
        });
    </script>
</x-app-layout>
