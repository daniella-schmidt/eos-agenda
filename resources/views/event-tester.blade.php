<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Teste de eventos</h2>
                <p class="mt-1 text-sm text-gray-500">Crie, consulte, atualize, cancele e exclua eventos.</p>
            </div>
            <span id="last-status" class="inline-flex w-fit rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                Pronto
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 xl:grid-cols-[minmax(420px,620px)_1fr]">
                <section class="space-y-6">
                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase text-blue-700">POST</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Criar evento</h3>
                            </div>
                            <code class="rounded bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-800">/api/events</code>
                        </div>

                        <form id="create-form" class="mt-5 space-y-4">
                            <div>
                                <label for="create-calendar" class="block text-sm font-medium text-gray-700">Calendario</label>
                                <select id="create-calendar" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Carregando calendarios...</option>
                                </select>
                            </div>

                            <div>
                                <label for="create-title" class="block text-sm font-medium text-gray-700">Titulo</label>
                                <input id="create-title" type="text" maxlength="200" required value="Reuniao de planejamento" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="create-description" class="block text-sm font-medium text-gray-700">Descricao</label>
                                <textarea id="create-description" rows="3" maxlength="2000" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">Evento criado pela tela de teste.</textarea>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="create-start" class="block text-sm font-medium text-gray-700">Inicio</label>
                                    <input id="create-start" type="datetime-local" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="create-end" class="block text-sm font-medium text-gray-700">Fim</label>
                                    <input id="create-end" type="datetime-local" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label for="create-status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select id="create-status" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="confirmed">confirmed</option>
                                        <option value="draft">draft</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="create-priority" class="block text-sm font-medium text-gray-700">Prioridade</label>
                                    <select id="create-priority" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="low">low</option>
                                        <option value="medium" selected>medium</option>
                                        <option value="high">high</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="create-timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                                    <input id="create-timezone" type="text" value="America/Sao_Paulo" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="create-location" class="block text-sm font-medium text-gray-700">Local</label>
                                    <input id="create-location" type="text" maxlength="500" value="Sala 01" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="create-url" class="block text-sm font-medium text-gray-700">URL da reuniao</label>
                                    <input id="create-url" type="url" maxlength="1000" placeholder="https://meet.example.com/reuniao" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <label class="flex items-center gap-3 rounded border border-gray-200 px-3 py-2 text-sm text-gray-700">
                                <input id="create-all-day" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                Evento de dia inteiro
                            </label>

                            <div class="rounded bg-gray-50 p-4">
                                <h4 class="text-sm font-semibold text-gray-900">Payload</h4>
                                <pre id="payload-preview" class="mt-3 max-h-52 overflow-auto whitespace-pre-wrap text-xs leading-5 text-gray-700"></pre>
                            </div>

                            <button type="submit" class="rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800">
                                Enviar POST
                            </button>
                        </form>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-base font-semibold text-gray-900">Listar e selecionar</h3>
                            <code class="rounded bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-800">GET /api/events</code>
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="filter-calendar" class="block text-sm font-medium text-gray-700">Calendario</label>
                                <select id="filter-calendar" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="">Todos</option>
                                </select>
                            </div>
                            <div>
                                <label for="filter-status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="filter-status" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="">Todos</option>
                                    <option value="draft">draft</option>
                                    <option value="confirmed">confirmed</option>
                                    <option value="cancelled">cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label for="filter-from" class="block text-sm font-medium text-gray-700">De</label>
                                <input id="filter-from" type="datetime-local" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="filter-to" class="block text-sm font-medium text-gray-700">Ate</label>
                                <input id="filter-to" type="datetime-local" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <button id="list-button" type="button" class="mt-4 rounded bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                            Enviar GET
                        </button>

                        <div class="mt-5">
                            <label for="event-id" class="block text-sm font-medium text-gray-700">Evento</label>
                            <select id="event-id" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                <option value="">Carregando eventos...</option>
                            </select>
                        </div>

                        <div class="mt-4 grid grid-cols-3 gap-3">
                            <button id="show-button" type="button" class="rounded bg-white px-3 py-2 text-sm font-medium text-gray-900 ring-1 ring-gray-300">GET por ID</button>
                            <button id="cancel-button" type="button" class="rounded bg-amber-600 px-3 py-2 text-sm font-medium text-white">Cancelar</button>
                            <button id="delete-button" type="button" class="rounded bg-red-600 px-3 py-2 text-sm font-medium text-white">DELETE</button>
                        </div>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-base font-semibold text-gray-900">Atualizar selecionado</h3>
                            <code class="rounded bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-800">PATCH</code>
                        </div>

                        <form id="update-form" class="mt-4 space-y-4">
                            <div>
                                <label for="update-title" class="block text-sm font-medium text-gray-700">Titulo</label>
                                <input id="update-title" type="text" maxlength="200" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="update-description" class="block text-sm font-medium text-gray-700">Descricao</label>
                                <textarea id="update-description" rows="3" maxlength="2000" class="mt-1 block w-full rounded border-gray-300 shadow-sm"></textarea>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="update-start" class="block text-sm font-medium text-gray-700">Inicio</label>
                                    <input id="update-start" type="datetime-local" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="update-end" class="block text-sm font-medium text-gray-700">Fim</label>
                                    <input id="update-end" type="datetime-local" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label for="update-calendar" class="block text-sm font-medium text-gray-700">Calendario</label>
                                    <select id="update-calendar" class="mt-1 block w-full rounded border-gray-300 shadow-sm"></select>
                                </div>
                                <div>
                                    <label for="update-status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select id="update-status" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                        <option value="draft">draft</option>
                                        <option value="confirmed">confirmed</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="update-priority" class="block text-sm font-medium text-gray-700">Prioridade</label>
                                    <select id="update-priority" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                        <option value="low">low</option>
                                        <option value="medium">medium</option>
                                        <option value="high">high</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Enviar PATCH</button>
                        </form>
                    </div>
                </section>

                <section class="self-start rounded bg-gray-950 p-5 shadow xl:sticky xl:top-6">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-base font-semibold text-white">Resposta</h3>
                        <button id="clear-response" type="button" class="rounded bg-white px-3 py-1.5 text-sm font-medium text-gray-900">Limpar</button>
                    </div>
                    <pre id="response" class="mt-4 min-h-[720px] overflow-auto whitespace-pre-wrap text-sm leading-6 text-gray-100"></pre>
                </section>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const output = document.getElementById('response');
        const lastStatus = document.getElementById('last-status');
        const eventSelect = document.getElementById('event-id');
        const payloadPreview = document.getElementById('payload-preview');
        let events = [];

        const field = (id) => document.getElementById(id);
        const nullable = (value) => value.trim() || null;

        function localDateTime(date) {
            const offset = date.getTimezoneOffset() * 60000;
            return new Date(date.getTime() - offset).toISOString().slice(0, 16);
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
                calendarId: Number(field('create-calendar').value),
                title: field('create-title').value,
                description: nullable(field('create-description').value),
                startAt: field('create-start').value,
                endAt: field('create-end').value,
                timezone: field('create-timezone').value,
                location: nullable(field('create-location').value),
                meetingURL: nullable(field('create-url').value),
                status: field('create-status').value,
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

        function renderPayload() {
            payloadPreview.textContent = JSON.stringify(createPayload(), null, 2);
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
            if (!eventSelect.value) {
                throw new Error('Selecione um evento.');
            }
            return eventSelect.value;
        }

        function fillCalendarSelect(select, calendars, includeAll = false) {
            const current = select.value;
            select.innerHTML = '';
            if (includeAll) {
                select.add(new Option('Todos', ''));
            }
            calendars.forEach((calendar) => {
                if (calendar.isActive) {
                    select.add(new Option(`${calendar.name} (#${calendar.id})`, calendar.id));
                }
            });
            if ([...select.options].some((option) => option.value === current)) {
                select.value = current;
            }
        }

        async function loadCalendars() {
            const response = await fetch('/api/calendars', { headers: { Accept: 'application/json' } });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data?.message || 'Nao foi possivel carregar os calendarios.');
            }
            const calendars = data.data ?? [];
            fillCalendarSelect(field('create-calendar'), calendars);
            fillCalendarSelect(field('update-calendar'), calendars);
            fillCalendarSelect(field('filter-calendar'), calendars, true);
            renderPayload();
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
                return;
            }
            eventSelect.disabled = false;
            eventSelect.add(new Option('Selecione um evento', ''));
            items.forEach((event) => {
                eventSelect.add(new Option(`#${event.id} - ${event.title} (${event.status})`, event.id));
            });
            if ([...eventSelect.options].some((option) => option.value === current)) {
                eventSelect.value = current;
            }
            fillUpdateForm();
        }

        function fillUpdateForm() {
            const event = events.find((item) => String(item.id) === eventSelect.value);
            if (!event) return;
            field('update-title').value = event.title ?? '';
            field('update-description').value = event.description ?? '';
            field('update-start').value = event.startAt ? localDateTime(new Date(event.startAt)) : '';
            field('update-end').value = event.endAt ? localDateTime(new Date(event.endAt)) : '';
            field('update-calendar').value = String(event.calendarId);
            field('update-status').value = event.status === 'cancelled' ? 'confirmed' : event.status;
            field('update-priority').value = event.priority;
        }

        async function request(method, url, body = null) {
            setStatus(`${method} ${url}`);
            const response = await fetch(url, {
                method,
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: body ? JSON.stringify(body) : null,
            });
            const text = await response.text();
            let data = null;
            try {
                data = text ? JSON.parse(text) : null;
            } catch {
                data = text;
            }
            output.textContent = JSON.stringify({
                request: { method, url, body },
                response: { status: response.status, ok: response.ok, data },
            }, null, 2);
            setStatus(`${response.status} ${response.ok ? 'OK' : 'erro'}`, response.ok);
            return { response, data };
        }

        async function loadEvents(preferredId = null, displayResponse = false) {
            const url = listUrl();
            const result = await request('GET', url);
            if (!result.response.ok) return;
            renderEvents(result.data?.data ?? [], preferredId);
            if (!displayResponse) {
                setStatus('Pronto');
            }
        }

        async function run(callback) {
            try {
                await callback();
            } catch (error) {
                setStatus('Erro', false);
                output.textContent = JSON.stringify({ error: error.message }, null, 2);
            }
        }

        field('create-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(async () => {
                const result = await request('POST', '/api/events', createPayload());
                if (result.response.ok) await loadEvents(result.data?.data?.id);
            });
        });

        field('update-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(async () => {
                const id = selectedId();
                const result = await request('PATCH', `/api/events/${id}`, updatePayload());
                if (result.response.ok) await loadEvents(id);
            });
        });

        field('list-button').addEventListener('click', () => run(() => loadEvents(null, true)));
        field('show-button').addEventListener('click', () => run(() => request('GET', `/api/events/${selectedId()}`)));
        field('cancel-button').addEventListener('click', () => run(async () => {
            const id = selectedId();
            const result = await request('POST', `/api/events/${id}/cancel`);
            if (result.response.ok) await loadEvents(id);
        }));
        field('delete-button').addEventListener('click', () => run(async () => {
            const result = await request('DELETE', `/api/events/${selectedId()}`);
            if (result.response.ok) await loadEvents();
        }));
        eventSelect.addEventListener('change', fillUpdateForm);
        field('clear-response').addEventListener('click', () => {
            output.textContent = '';
            setStatus('Pronto');
        });

        document.querySelectorAll('#create-form input, #create-form textarea, #create-form select').forEach((input) => {
            input.addEventListener('input', renderPayload);
            input.addEventListener('change', renderPayload);
        });

        initializeDates();
        renderPayload();
        run(async () => {
            await loadCalendars();
            await loadEvents();
        });
    </script>
</x-app-layout>
