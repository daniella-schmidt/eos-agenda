<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Teste de lembretes</h2>
                <p class="mt-1 text-sm text-gray-500">Teste todos os endpoints de lembretes de eventos.</p>
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
                        <h3 class="text-lg font-semibold text-gray-900">Evento</h3>
                        <label for="event-id" class="mt-4 block text-sm font-medium text-gray-700">Evento selecionado</label>
                        <div class="mt-1 flex flex-col gap-3 sm:flex-row">
                            <select id="event-id" class="block w-full rounded border-gray-300 shadow-sm">
                                <option value="">Carregando eventos...</option>
                            </select>
                            <button id="reload-events" type="button" class="shrink-0 rounded bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                                Recarregar
                            </button>
                        </div>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase text-blue-700">GET</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Listar lembretes</h3>
                            </div>
                            <code class="rounded bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-800">/api/events/{event}/reminders</code>
                        </div>
                        <button id="list-button" type="button" class="mt-5 rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white">
                            Enviar GET
                        </button>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase text-green-700">POST</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Criar lembrete</h3>
                            </div>
                            <code class="rounded bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-800">/api/events/{event}/reminders</code>
                        </div>
                        <form id="create-form" class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="create-type" class="block text-sm font-medium text-gray-700">Tipo</label>
                                <select id="create-type" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="notification">notification</option>
                                    <option value="email">email</option>
                                    <option value="whatsapp">whatsapp</option>
                                </select>
                            </div>
                            <div>
                                <label for="create-minutes" class="block text-sm font-medium text-gray-700">Minutos antes</label>
                                <input id="create-minutes" type="number" min="0" max="10080" value="30" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </div>
                            <button type="submit" class="w-fit rounded bg-green-700 px-4 py-2 text-sm font-semibold text-white">
                                Enviar POST
                            </button>
                        </form>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <h3 class="text-lg font-semibold text-gray-900">Lembrete selecionado</h3>
                        <label for="reminder-id" class="mt-4 block text-sm font-medium text-gray-700">Lembrete</label>
                        <select id="reminder-id" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            <option value="">Liste os lembretes de um evento</option>
                        </select>

                        <div class="mt-4 grid gap-3 sm:grid-cols-3">
                            <button id="show-button" type="button" class="rounded bg-blue-700 px-3 py-2 text-sm font-semibold text-white">GET por ID</button>
                            <button id="sent-button" type="button" class="rounded bg-amber-600 px-3 py-2 text-sm font-semibold text-white">Marcar enviado</button>
                            <button id="delete-button" type="button" class="rounded bg-red-700 px-3 py-2 text-sm font-semibold text-white">DELETE</button>
                        </div>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase text-purple-700">PATCH</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Atualizar lembrete</h3>
                            </div>
                            <code class="rounded bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-800">/api/event-reminders/{id}</code>
                        </div>
                        <form id="update-form" class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="update-type" class="block text-sm font-medium text-gray-700">Tipo</label>
                                <select id="update-type" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="notification">notification</option>
                                    <option value="email">email</option>
                                    <option value="whatsapp">whatsapp</option>
                                </select>
                            </div>
                            <div>
                                <label for="update-minutes" class="block text-sm font-medium text-gray-700">Minutos antes</label>
                                <input id="update-minutes" type="number" min="0" max="10080" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </div>
                            <button type="submit" class="w-fit rounded bg-purple-700 px-4 py-2 text-sm font-semibold text-white">
                                Enviar PATCH
                            </button>
                        </form>
                    </div>
                </section>

                <section class="self-start rounded bg-gray-950 p-5 shadow xl:sticky xl:top-6">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="font-semibold text-white">Resposta</h3>
                        <button id="clear-output" type="button" class="rounded bg-white px-3 py-1.5 text-sm font-medium text-gray-900">Limpar</button>
                    </div>
                    <pre id="output" class="mt-4 min-h-[720px] overflow-auto whitespace-pre-wrap text-sm leading-6 text-gray-100">Execute um endpoint para visualizar a resposta.</pre>
                </section>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const eventSelect = document.getElementById('event-id');
        const reminderSelect = document.getElementById('reminder-id');
        const output = document.getElementById('output');
        const statusBadge = document.getElementById('last-status');
        let reminders = [];

        function setStatus(text, successful = null) {
            statusBadge.textContent = text;
            statusBadge.className = 'inline-flex w-fit rounded px-3 py-1 text-xs font-medium';
            statusBadge.classList.add(
                successful === true ? 'bg-green-100' : successful === false ? 'bg-red-100' : 'bg-gray-100',
                successful === true ? 'text-green-800' : successful === false ? 'text-red-800' : 'text-gray-700',
            );
        }

        function selectedEventId() {
            if (!eventSelect.value) throw new Error('Selecione um evento.');
            return eventSelect.value;
        }

        function selectedReminderId() {
            if (!reminderSelect.value) throw new Error('Selecione um lembrete.');
            return reminderSelect.value;
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
                body: body === null ? null : JSON.stringify(body),
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
            setStatus(`${response.status} ${response.ok ? 'OK' : 'Erro'}`, response.ok);

            return { response, data };
        }

        async function run(callback) {
            try {
                await callback();
            } catch (error) {
                setStatus('Erro', false);
                output.textContent = JSON.stringify({ error: error.message }, null, 2);
            }
        }

        async function loadEvents() {
            const result = await request('GET', '/api/events?perPage=100');
            if (!result.response.ok) return;

            const current = eventSelect.value;
            const events = result.data?.data ?? [];
            eventSelect.innerHTML = '';
            eventSelect.add(new Option('Selecione um evento', ''));
            events.forEach((event) => eventSelect.add(new Option(`#${event.id} - ${event.title}`, event.id)));

            if ([...eventSelect.options].some((option) => option.value === current)) {
                eventSelect.value = current;
            }
        }

        function renderReminders(items, preferredId = null) {
            reminders = items;
            const current = preferredId ? String(preferredId) : reminderSelect.value;
            reminderSelect.innerHTML = '';

            if (!items.length) {
                reminderSelect.add(new Option('Nenhum lembrete encontrado', ''));
                fillUpdateForm();
                return;
            }

            reminderSelect.add(new Option('Selecione um lembrete', ''));
            items.forEach((reminder) => {
                const sent = reminder.isSent ? 'enviado' : 'pendente';
                reminderSelect.add(new Option(
                    `#${reminder.id} - ${reminder.type}, ${reminder.minutesBefore} min (${sent})`,
                    reminder.id,
                ));
            });

            if ([...reminderSelect.options].some((option) => option.value === current)) {
                reminderSelect.value = current;
            }
            fillUpdateForm();
        }

        function fillUpdateForm() {
            const reminder = reminders.find((item) => String(item.id) === reminderSelect.value);
            document.getElementById('update-type').value = reminder?.type ?? 'notification';
            document.getElementById('update-minutes').value = reminder?.minutesBefore ?? '';
        }

        async function loadReminders(preferredId = null) {
            const result = await request('GET', `/api/events/${selectedEventId()}/reminders`);
            if (result.response.ok) renderReminders(result.data?.data ?? [], preferredId);
            return result;
        }

        document.getElementById('reload-events').addEventListener('click', () => run(loadEvents));
        document.getElementById('list-button').addEventListener('click', () => run(loadReminders));
        eventSelect.addEventListener('change', () => {
            reminders = [];
            renderReminders([]);
            if (eventSelect.value) run(loadReminders);
        });
        reminderSelect.addEventListener('change', fillUpdateForm);

        document.getElementById('create-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(async () => {
                const result = await request('POST', `/api/events/${selectedEventId()}/reminders`, {
                    type: document.getElementById('create-type').value,
                    minutesBefore: Number(document.getElementById('create-minutes').value),
                });
                if (result.response.ok) await loadReminders(result.data?.data?.id);
            });
        });

        document.getElementById('show-button').addEventListener('click', () => run(() =>
            request('GET', `/api/event-reminders/${selectedReminderId()}`)
        ));

        document.getElementById('update-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(async () => {
                const id = selectedReminderId();
                const result = await request('PATCH', `/api/event-reminders/${id}`, {
                    type: document.getElementById('update-type').value,
                    minutesBefore: Number(document.getElementById('update-minutes').value),
                });
                if (result.response.ok) await loadReminders(id);
            });
        });

        document.getElementById('sent-button').addEventListener('click', () => run(async () => {
            const id = selectedReminderId();
            const result = await request('POST', `/api/event-reminders/${id}/mark-as-sent`, {});
            if (result.response.ok) await loadReminders(id);
        }));

        document.getElementById('delete-button').addEventListener('click', () => run(async () => {
            const result = await request('DELETE', `/api/event-reminders/${selectedReminderId()}`);
            if (result.response.ok) await loadReminders();
        }));

        document.getElementById('clear-output').addEventListener('click', () => {
            output.textContent = 'Execute um endpoint para visualizar a resposta.';
            setStatus('Pronto');
        });

        run(loadEvents);
    </script>
</x-app-layout>
