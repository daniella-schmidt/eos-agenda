<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Teste de contatos</h2>
                <p class="mt-1 text-sm text-gray-500">Crie, liste, consulte, atualize e exclua contatos.</p>
            </div>
            <span id="status" class="rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">Pronto</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:px-8 xl:grid-cols-[minmax(420px,620px)_1fr]">
            <section class="space-y-6">
                <div class="rounded bg-white p-5 shadow">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-gray-900">Criar contato</h3>
                        <code class="rounded bg-green-50 px-3 py-1 text-xs font-semibold text-green-800">POST /api/contacts</code>
                    </div>
                    <form id="create-form" class="mt-5 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="text-sm font-medium text-gray-700">Nome
                                <input id="create-name" required maxlength="120" value="Maria Silva" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                            <label class="text-sm font-medium text-gray-700">E-mail
                                <input id="create-email" type="email" maxlength="180" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                            <label class="text-sm font-medium text-gray-700">Telefone
                                <input id="create-phone" maxlength="40" value="(49) 99999-9999" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                            <label class="text-sm font-medium text-gray-700">Empresa
                                <input id="create-company" maxlength="120" value="Empresa Exemplo" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                        </div>
                        <label class="block text-sm font-medium text-gray-700">Observacoes
                            <textarea id="create-notes" maxlength="1000" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm">Contato criado pela tela de teste.</textarea>
                        </label>
                        <button class="rounded bg-green-700 px-4 py-2 text-sm font-semibold text-white">Enviar POST</button>
                    </form>
                </div>

                <div class="rounded bg-white p-5 shadow">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-gray-900">Listar contatos</h3>
                        <code class="rounded bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-800">GET /api/contacts</code>
                    </div>
                    <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                        <input id="search" placeholder="Pesquisar por nome, e-mail ou empresa" class="block w-full rounded border-gray-300 shadow-sm">
                        <button id="list-button" type="button" class="shrink-0 rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white">Enviar GET</button>
                    </div>
                    <label class="mt-4 block text-sm font-medium text-gray-700">Contato selecionado
                        <select id="contact-id" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            <option value="">Carregando contatos...</option>
                        </select>
                    </label>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <button id="show-button" type="button" class="rounded bg-gray-900 px-3 py-2 text-sm font-semibold text-white">GET por ID</button>
                        <button id="delete-button" type="button" class="rounded bg-red-700 px-3 py-2 text-sm font-semibold text-white">DELETE</button>
                    </div>
                </div>

                <div class="rounded bg-white p-5 shadow">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-gray-900">Atualizar contato</h3>
                        <code class="rounded bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-800">PATCH /api/contacts/{id}</code>
                    </div>
                    <form id="update-form" class="mt-5 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="text-sm font-medium text-gray-700">Nome
                                <input id="update-name" required maxlength="120" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                            <label class="text-sm font-medium text-gray-700">E-mail
                                <input id="update-email" type="email" maxlength="180" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                            <label class="text-sm font-medium text-gray-700">Telefone
                                <input id="update-phone" maxlength="40" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                            <label class="text-sm font-medium text-gray-700">Empresa
                                <input id="update-company" maxlength="120" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                        </div>
                        <label class="block text-sm font-medium text-gray-700">Observacoes
                            <textarea id="update-notes" maxlength="1000" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm"></textarea>
                        </label>
                        <button class="rounded bg-purple-700 px-4 py-2 text-sm font-semibold text-white">Enviar PATCH</button>
                    </form>
                </div>
            </section>

            <section class="self-start rounded bg-gray-950 p-5 shadow xl:sticky xl:top-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-white">Resposta</h3>
                    <button id="clear" type="button" class="rounded bg-white px-3 py-1.5 text-sm font-medium text-gray-900">Limpar</button>
                </div>
                <pre id="output" class="mt-4 min-h-[720px] overflow-auto whitespace-pre-wrap text-sm leading-6 text-gray-100">Execute um endpoint.</pre>
            </section>
        </div>
    </div>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const select = document.getElementById('contact-id');
        const output = document.getElementById('output');
        const statusBadge = document.getElementById('status');
        let contacts = [];
        const el = (id) => document.getElementById(id);
        const nullable = (value) => value.trim() || null;

        function selectedId() {
            if (!select.value) throw new Error('Selecione um contato.');
            return select.value;
        }

        function setStatus(text, ok = null) {
            statusBadge.textContent = text;
            statusBadge.className = 'rounded px-3 py-1 text-xs font-medium';
            statusBadge.classList.add(ok === true ? 'bg-green-100' : ok === false ? 'bg-red-100' : 'bg-gray-100');
            statusBadge.classList.add(ok === true ? 'text-green-800' : ok === false ? 'text-red-800' : 'text-gray-700');
        }

        async function request(method, url, body = null) {
            setStatus(`${method} ${url}`);
            const response = await fetch(url, {
                method,
                headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: body === null ? null : JSON.stringify(body),
            });
            const text = await response.text();
            let data;
            try { data = text ? JSON.parse(text) : null; } catch { data = text; }
            output.textContent = JSON.stringify({ request: { method, url, body }, response: { status: response.status, ok: response.ok, data } }, null, 2);
            setStatus(`${response.status} ${response.ok ? 'OK' : 'Erro'}`, response.ok);
            return { response, data };
        }

        async function run(callback) {
            try { await callback(); } catch (error) {
                setStatus('Erro', false);
                output.textContent = JSON.stringify({ error: error.message }, null, 2);
            }
        }

        function payload(prefix) {
            return {
                name: el(`${prefix}-name`).value,
                email: nullable(el(`${prefix}-email`).value),
                phone: nullable(el(`${prefix}-phone`).value),
                company: nullable(el(`${prefix}-company`).value),
                notes: nullable(el(`${prefix}-notes`).value),
            };
        }

        function render(items, preferredId = null) {
            contacts = items;
            const current = preferredId ? String(preferredId) : select.value;
            select.innerHTML = '<option value="">Selecione um contato</option>';
            items.forEach((contact) => select.add(new Option(`#${contact.id} - ${contact.name}`, contact.id)));
            if ([...select.options].some((option) => option.value === current)) select.value = current;
            fillUpdate();
        }

        function fillUpdate() {
            const contact = contacts.find((item) => String(item.id) === select.value);
            ['name', 'email', 'phone', 'company', 'notes'].forEach((field) => {
                el(`update-${field}`).value = contact?.[field] ?? '';
            });
        }

        async function loadContacts(preferredId = null) {
            const params = new URLSearchParams({ perPage: '100' });
            if (el('search').value.trim()) params.set('search', el('search').value.trim());
            const result = await request('GET', `/api/contacts?${params}`);
            if (result.response.ok) render(result.data?.data ?? [], preferredId);
        }

        el('create-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(async () => {
                const result = await request('POST', '/api/contacts', payload('create'));
                if (result.response.ok) await loadContacts(result.data?.data?.id);
            });
        });
        el('update-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(async () => {
                const id = selectedId();
                const result = await request('PATCH', `/api/contacts/${id}`, payload('update'));
                if (result.response.ok) await loadContacts(id);
            });
        });
        el('list-button').addEventListener('click', () => run(loadContacts));
        el('show-button').addEventListener('click', () => run(() => request('GET', `/api/contacts/${selectedId()}`)));
        el('delete-button').addEventListener('click', () => run(async () => {
            const result = await request('DELETE', `/api/contacts/${selectedId()}`);
            if (result.response.ok) await loadContacts();
        }));
        select.addEventListener('change', fillUpdate);
        el('clear').addEventListener('click', () => { output.textContent = 'Execute um endpoint.'; setStatus('Pronto'); });
        run(loadContacts);
    </script>
</x-app-layout>
