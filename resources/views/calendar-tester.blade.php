<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Teste de calendarios
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    POST em destaque, com retorno bruto da API ao lado.
                </p>
            </div>
            <span id="last-status" class="inline-flex w-fit rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                Pronto
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 xl:grid-cols-[minmax(360px,520px)_1fr]">
                <section class="space-y-6">
                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">POST</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Criar calendario</h3>
                            </div>
                            <code class="rounded bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-800">/api/calendars</code>
                        </div>

                        <form id="create-form" class="mt-5 space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="create-name">Nome</label>
                                <input id="create-name" name="name" type="text" value="Trabalho" maxlength="120" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="create-description">Descricao</label>
                                <textarea id="create-description" name="description" rows="4" maxlength="1000" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">Calendario criado pela tela de teste.</textarea>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-[96px_1fr]">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="create-color">Cor</label>
                                    <input id="create-color" name="color" type="color" value="#2563eb" class="mt-1 h-10 w-full rounded border border-gray-300 bg-white p-1">
                                </div>

                                <label class="mt-6 flex h-10 items-center gap-3 rounded border border-gray-200 px-3 text-sm text-gray-700">
                                    <input id="create-default" name="isDefault" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    Definir como calendario padrao
                                </label>
                            </div>

                            <div class="rounded bg-gray-50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <h4 class="text-sm font-semibold text-gray-900">Payload</h4>
                                    <button id="refresh-payload" type="button" class="rounded bg-white px-3 py-1.5 text-xs font-medium text-gray-700 ring-1 ring-gray-300">
                                        Atualizar
                                    </button>
                                </div>
                                <pre id="payload-preview" class="mt-3 max-h-48 overflow-auto whitespace-pre-wrap text-xs leading-5 text-gray-700"></pre>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <button type="submit" class="inline-flex justify-center rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800">
                                    Enviar POST
                                </button>
                                <button id="reset-create" type="button" class="inline-flex justify-center rounded bg-white px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 hover:bg-gray-50">
                                    Restaurar exemplo
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <h3 class="text-base font-semibold text-gray-900">Outros testes</h3>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700" for="calendar-id">Calendario</label>
                            <select id="calendar-id" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Carregando calendarios...</option>
                            </select>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <button type="button" data-action="list" class="rounded bg-gray-900 px-3 py-2 text-sm font-medium text-white">
                                GET lista
                            </button>
                            <button type="button" data-action="show" class="rounded bg-white px-3 py-2 text-sm font-medium text-gray-900 ring-1 ring-gray-300">
                                GET por ID
                            </button>
                            <button type="button" data-action="make-default" class="rounded bg-white px-3 py-2 text-sm font-medium text-gray-900 ring-1 ring-gray-300">
                                POST padrao
                            </button>
                            <button type="button" data-action="delete" class="rounded bg-red-600 px-3 py-2 text-sm font-medium text-white">
                                DELETE
                            </button>
                        </div>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <h3 class="text-base font-semibold text-gray-900">Atualizar selecionado</h3>

                        <form id="update-form" class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="update-name">Nome</label>
                                <input id="update-name" name="name" type="text" value="Trabalho atualizado" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="update-description">Descricao</label>
                                <textarea id="update-description" name="description" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">Atualizado pela tela de teste.</textarea>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-[96px_1fr]">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="update-color">Cor</label>
                                    <input id="update-color" name="color" type="color" value="#16a34a" class="mt-1 h-10 w-full rounded border border-gray-300 bg-white p-1">
                                </div>

                                <label class="mt-6 flex h-10 items-center gap-3 rounded border border-gray-200 px-3 text-sm text-gray-700">
                                    <input id="update-active" name="isActive" type="checkbox" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    Ativo
                                </label>
                            </div>

                            <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">
                                Enviar PATCH
                            </button>
                        </form>
                    </div>
                </section>

                <section class="grid gap-6 lg:grid-cols-2 xl:grid-cols-1">
                    <div class="rounded bg-gray-950 p-5 shadow">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-base font-semibold text-white">Resposta</h3>
                            <button id="clear-response" type="button" class="rounded bg-white px-3 py-1.5 text-sm font-medium text-gray-900">
                                Limpar
                            </button>
                        </div>

                        <pre id="response" class="mt-4 min-h-[360px] overflow-auto whitespace-pre-wrap text-sm leading-6 text-gray-100"></pre>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <h3 class="text-base font-semibold text-gray-900">Ultimo calendario criado</h3>
                        <dl class="mt-4 grid gap-3 text-sm">
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <dt class="font-medium text-gray-500">ID</dt>
                                <dd id="created-id" class="font-semibold text-gray-900">-</dd>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <dt class="font-medium text-gray-500">Nome</dt>
                                <dd id="created-name" class="font-semibold text-gray-900">-</dd>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <dt class="font-medium text-gray-500">Padrao</dt>
                                <dd id="created-default" class="font-semibold text-gray-900">-</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="font-medium text-gray-500">Cor</dt>
                                <dd class="flex items-center gap-2 font-semibold text-gray-900">
                                    <span id="created-color-dot" class="h-4 w-4 rounded border border-gray-300"></span>
                                    <span id="created-color">-</span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const output = document.getElementById('response');
        const calendarId = document.getElementById('calendar-id');
        const lastStatus = document.getElementById('last-status');
        const payloadPreview = document.getElementById('payload-preview');

        const example = {
            name: 'Trabalho',
            description: 'Calendario criado pela tela de teste.',
            color: '#2563eb',
            isDefault: false,
        };

        function createPayload() {
            return {
                name: document.getElementById('create-name').value,
                description: document.getElementById('create-description').value || null,
                color: document.getElementById('create-color').value || null,
                isDefault: document.getElementById('create-default').checked,
            };
        }

        function updatePayload() {
            return {
                name: document.getElementById('update-name').value,
                description: document.getElementById('update-description').value || null,
                color: document.getElementById('update-color').value || null,
                isActive: document.getElementById('update-active').checked,
            };
        }

        function renderPayloadPreview() {
            payloadPreview.textContent = JSON.stringify(createPayload(), null, 2);
        }

        function selectedId() {
            if (!calendarId.value) {
                throw new Error('Selecione um calendario.');
            }

            return calendarId.value;
        }

        function renderCalendarOptions(calendars, preferredId = null) {
            const selectedValue = preferredId ? String(preferredId) : calendarId.value;

            calendarId.innerHTML = '';

            if (!calendars.length) {
                calendarId.add(new Option('Nenhum calendario disponivel', ''));
                calendarId.disabled = true;
                return;
            }

            calendarId.disabled = false;
            calendarId.add(new Option('Selecione um calendario', ''));

            calendars.forEach((calendar) => {
                const details = [
                    `#${calendar.id}`,
                    calendar.isDefault ? 'padrao' : null,
                    calendar.isActive ? null : 'inativo',
                ].filter(Boolean).join(' - ');

                calendarId.add(new Option(`${calendar.name} (${details})`, calendar.id));
            });

            if ([...calendarId.options].some((option) => option.value === selectedValue)) {
                calendarId.value = selectedValue;
            }
        }

        async function loadCalendars(preferredId = null) {
            const response = await fetch('/api/calendars', {
                headers: {
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data?.message || 'Nao foi possivel carregar os calendarios.');
            }

            renderCalendarOptions(data.data ?? [], preferredId);
        }

        function setStatus(text, ok = null) {
            lastStatus.textContent = text;
            lastStatus.className = 'inline-flex w-fit rounded px-3 py-1 text-xs font-medium';

            if (ok === true) {
                lastStatus.classList.add('bg-green-100', 'text-green-800');
                return;
            }

            if (ok === false) {
                lastStatus.classList.add('bg-red-100', 'text-red-800');
                return;
            }

            lastStatus.classList.add('bg-gray-100', 'text-gray-700');
        }

        function showCreated(calendar) {
            document.getElementById('created-id').textContent = calendar?.id ?? '-';
            document.getElementById('created-name').textContent = calendar?.name ?? '-';
            document.getElementById('created-default').textContent = calendar?.isDefault ? 'Sim' : 'Nao';
            document.getElementById('created-color').textContent = calendar?.color ?? '-';
            document.getElementById('created-color-dot').style.backgroundColor = calendar?.color ?? 'transparent';
        }

        async function request(method, url, body = null) {
            setStatus(`${method} ${url}`);

            const response = await fetch(url, {
                method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: body ? JSON.stringify(body) : null,
            });

            const text = await response.text();
            const data = text ? JSON.parse(text) : null;

            output.textContent = JSON.stringify({
                request: { method, url, body },
                response: {
                    status: response.status,
                    ok: response.ok,
                    data,
                },
            }, null, 2);

            setStatus(`${response.status} ${response.ok ? 'OK' : 'erro'}`, response.ok);

            if (data?.data?.id) {
                calendarId.value = data.data.id;
            }

            if (method === 'POST' && url === '/api/calendars' && data?.data) {
                showCreated(data.data);
            }

            if (response.ok && method === 'GET' && url === '/api/calendars') {
                renderCalendarOptions(data?.data ?? []);
            }

            if (response.ok && method !== 'GET') {
                await loadCalendars(data?.data?.id);
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

        document.getElementById('create-form').addEventListener('submit', (event) => {
            event.preventDefault();
            renderPayloadPreview();
            run(() => request('POST', '/api/calendars', createPayload()));
        });

        document.getElementById('update-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(() => request('PATCH', `/api/calendars/${selectedId()}`, updatePayload()));
        });

        document.querySelectorAll('[data-action]').forEach((button) => {
            button.addEventListener('click', () => {
                const action = button.dataset.action;

                run(() => {
                    if (action === 'list') {
                        return request('GET', '/api/calendars');
                    }

                    if (action === 'show') {
                        return request('GET', `/api/calendars/${selectedId()}`);
                    }

                    if (action === 'make-default') {
                        return request('POST', `/api/calendars/${selectedId()}/make-default`);
                    }

                    return request('DELETE', `/api/calendars/${selectedId()}`);
                });
            });
        });

        document.getElementById('refresh-payload').addEventListener('click', renderPayloadPreview);

        document.getElementById('reset-create').addEventListener('click', () => {
            document.getElementById('create-name').value = example.name;
            document.getElementById('create-description').value = example.description;
            document.getElementById('create-color').value = example.color;
            document.getElementById('create-default').checked = example.isDefault;
            renderPayloadPreview();
        });

        document.getElementById('clear-response').addEventListener('click', () => {
            output.textContent = '';
            setStatus('Pronto');
        });

        document.querySelectorAll('#create-form input, #create-form textarea').forEach((input) => {
            input.addEventListener('input', renderPayloadPreview);
            input.addEventListener('change', renderPayloadPreview);
        });

        renderPayloadPreview();
        run(loadCalendars);
    </script>
</x-app-layout>
