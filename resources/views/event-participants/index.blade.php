<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Participantes</h2>
                <p class="mt-1 text-sm text-gray-500">Gerencie participantes vinculados aos eventos.</p>
            </div>
            <span id="status" class="rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">Pronto</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <section class="space-y-6">

                <div class="rounded bg-white p-5 shadow">
                    <h3 class="text-lg font-semibold text-gray-900">Evento</h3>
                    <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                        <select id="event-id" class="block w-full rounded border-gray-300 shadow-sm">
                            <option value="">Carregando eventos...</option>
                        </select>
                        <button id="reload-button" type="button" class="shrink-0 rounded bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Recarregar</button>
                    </div>
                </div>

                <div class="rounded bg-white p-5 shadow">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-gray-900">Listar participantes</h3>
                        <span class="rounded bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-800">Consulta</span>

                    </div>
                    <button id="list-button" type="button" class="mt-5 rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white">Atualizar lista</button>
                </div>

                <div class="rounded bg-white p-5 shadow">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-gray-900">Adicionar participante</h3>
                        <span class="rounded bg-green-50 px-3 py-1 text-xs font-semibold text-green-800">Adicionar</span>

                    </div>
                    <form id="create-form" class="mt-5 space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Contato existente (opcional)
                            <select id="create-contact" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                <option value="">Sem contato vinculado</option>
                            </select>
                        </label>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="text-sm font-medium text-gray-700">Nome
                                <input id="create-name" maxlength="120" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                            <label class="text-sm font-medium text-gray-700">E-mail
                                <input id="create-email" type="email" maxlength="180" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                        </div>
                        <label class="block text-sm font-medium text-gray-700">Papel
                            <select id="create-role" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                <option value="attendee">Participante</option>
                                <option value="organizer">Organizador</option>
                            </select>
                        </label>
                        <button class="rounded bg-green-700 px-4 py-2 text-sm font-semibold text-white">Salvar</button>
                    </form>
                </div>

                <div class="rounded bg-white p-5 shadow">
                    <h3 class="text-lg font-semibold text-gray-900">Participante selecionado</h3>
                    <select id="participant-id" class="mt-4 block w-full rounded border-gray-300 shadow-sm">
                        <option value="">Liste os participantes de um evento</option>
                    </select>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <button id="show-button" type="button" class="rounded bg-gray-900 px-3 py-2 text-sm font-semibold text-white">Ver detalhes</button>
                        <button id="delete-button" type="button" class="rounded bg-red-700 px-3 py-2 text-sm font-semibold text-white">Excluir</button>
                    </div>
                </div>

                <div class="rounded bg-white p-5 shadow">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-gray-900">Atualizar participante</h3>
                        <span class="rounded bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-800">Edição</span>

                    </div>
                    <form id="update-form" class="mt-5 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="text-sm font-medium text-gray-700">Nome
                                <input id="update-name" maxlength="120" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                            <label class="text-sm font-medium text-gray-700">E-mail
                                <input id="update-email" type="email" maxlength="180" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </label>
                            <label class="text-sm font-medium text-gray-700">Papel
                                <select id="update-role" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="attendee">Participante</option>
                                    <option value="organizer">Organizador</option>
                                </select>
                            </label>
                            <label class="text-sm font-medium text-gray-700">Resultado
                                <select id="update-response" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="pending">Pendente</option>
                                    <option value="accepted">Aceito</option>
                                    <option value="declined">Recusado</option>
                                    <option value="tentative">Talvez</option>
                                </select>
                            </label>
                        </div>
                        <button class="rounded bg-purple-700 px-4 py-2 text-sm font-semibold text-white">Salvar alterações</button>
                    </form>
                </div>
            </section>

            <div id="output" class="rounded bg-gray-950 p-5 shadow text-sm font-semibold leading-6 text-gray-100">
                <button id="clear" type="button" class="mb-4 rounded bg-white px-3 py-1.5 text-sm font-medium text-gray-900">Limpar</button>
                <div class="rounded bg-white/10 p-4">Escolha uma ação para acompanhar o resultado aqui.</div>
            </div>


        </div>
    </div>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const eventSelect = document.getElementById('event-id');
        const participantSelect = document.getElementById('participant-id');
        const output = document.getElementById('output');
        const statusBadge = document.getElementById('status');
        const el = (id) => document.getElementById(id);
        let participants = [];

        function eventId() {
            if (!eventSelect.value) throw new Error('Selecione um evento.');
            return eventSelect.value;
        }

        function participantId() {
            if (!participantSelect.value) throw new Error('Selecione um participante.');
            return participantSelect.value;
        }

        function setStatus(text, ok = null) {
            statusBadge.textContent = text;
            statusBadge.className = 'rounded px-3 py-1 text-xs font-medium';
            statusBadge.classList.add(ok === true ? 'bg-green-100' : ok === false ? 'bg-red-100' : 'bg-gray-100');
            statusBadge.classList.add(ok === true ? 'text-green-800' : ok === false ? 'text-red-800' : 'text-gray-700');
        }

        function setAlert(message, variant = 'neutral') {
            const base = 'mt-4 rounded p-4 text-sm font-semibold leading-6';

            const variants = {
                neutral: 'bg-white/10 text-gray-100',
                success: 'bg-green-50 text-green-800',
                error: 'bg-red-50 text-red-800',
                loading: 'bg-blue-50 text-blue-800',
            };
            output.className = base + ' ' + (variants[variant] || variants.neutral);
            output.textContent = message || '';
        }

        async function request(method, url, body = null) {
            setStatus('Carregando...');
            const response = await fetch(url, {
                method,
                headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: body === null ? null : JSON.stringify(body),
            });

            let data = null;
            try {
                data = await response.json();
            } catch {
                data = null;
            }

            const msg = response.ok
                ? 'Ação concluída com sucesso.'
                : (data?.message || data?.error || 'Não foi possível concluir a ação.');

            setAlert(msg, response.ok ? 'success' : 'error');
            setStatus(response.ok ? 'Concluído' : 'Erro', response.ok);
            return { response, data };
        }


        async function run(callback) {
            try { await callback(); } catch (error) {
                setStatus('Erro', false);
                output.textContent = error.message;
            }
        }

        async function loadDependencies() {
            const [eventsResult, contactsResult] = await Promise.all([
                request('GET', '/api/events?perPage=100'),
                fetch('/api/contacts?perPage=100', { headers: { Accept: 'application/json' } }).then(async (response) => ({ response, data: await response.json() })),
            ]);
            if (eventsResult.response.ok) {
                const current = eventSelect.value;
                eventSelect.innerHTML = '<option value="">Selecione um evento</option>';
                (eventsResult.data?.data ?? []).forEach((event) => eventSelect.add(new Option(event.title, event.id)));
                if ([...eventSelect.options].some((option) => option.value === current)) eventSelect.value = current;
            }
            if (contactsResult.response.ok) {
                const contactSelect = el('create-contact');
                contactSelect.innerHTML = '<option value="">Sem contato vinculado</option>';
                (contactsResult.data?.data ?? []).forEach((contact) => contactSelect.add(new Option(contact.name, contact.id)));
            }
        }

        function renderParticipants(items, preferredId = null) {
            participants = items;
            const current = preferredId ? String(preferredId) : participantSelect.value;
            participantSelect.innerHTML = '<option value="">Selecione um participante</option>';
            items.forEach((participant) => participantSelect.add(new Option(
                `${participant.name || participant.email || 'Sem nome'} (${participant.role === 'organizer' ? 'Organizador' : 'Participante'})`,
                participant.id,
            )));
            if ([...participantSelect.options].some((option) => option.value === current)) participantSelect.value = current;
            fillUpdate();
        }

        function fillUpdate() {
            const participant = participants.find((item) => String(item.id) === participantSelect.value);
            el('update-name').value = participant?.name ?? '';
            el('update-email').value = participant?.email ?? '';
            el('update-role').value = participant?.role ?? 'attendee';
            el('update-response').value = participant?.responseStatus ?? 'pending';
        }

        async function loadParticipants(preferredId = null) {
            const result = await request('GET', `/api/events/${eventId()}/participants`);
            if (result.response.ok) renderParticipants(result.data?.data ?? [], preferredId);
        }

        eventSelect.addEventListener('change', () => {
            renderParticipants([]);
            if (eventSelect.value) run(loadParticipants);
        });
        participantSelect.addEventListener('change', fillUpdate);
        el('reload-button').addEventListener('click', () => run(loadDependencies));
        el('list-button').addEventListener('click', () => run(loadParticipants));
        el('create-contact').addEventListener('change', () => {
            const option = el('create-contact').selectedOptions[0];
            if (option?.value) el('create-name').value = option.text.replace(/^#\d+ - /, '');
        });
        el('create-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(async () => {
                const result = await request('POST', `/api/events/${eventId()}/participants`, {
                    contactId: el('create-contact').value ? Number(el('create-contact').value) : null,
                    name: el('create-name').value.trim() || null,
                    email: el('create-email').value.trim() || null,
                    role: el('create-role').value,
                });
                if (result.response.ok) await loadParticipants(result.data?.data?.id);
            });
        });
        el('update-form').addEventListener('submit', (event) => {
            event.preventDefault();
            run(async () => {
                const id = participantId();
                const result = await request('PATCH', `/api/event-participants/${id}`, {
                    name: el('update-name').value.trim() || null,
                    email: el('update-email').value.trim() || null,
                    role: el('update-role').value,
                    responseStatus: el('update-response').value,
                });
                if (result.response.ok) await loadParticipants(id);
            });
        });
        el('show-button').addEventListener('click', () => run(() => request('GET', `/api/event-participants/${participantId()}`)));
        el('delete-button').addEventListener('click', () => run(async () => {
            if (!confirm('Excluir este participante do evento?')) return;
            const result = await request('DELETE', `/api/event-participants/${participantId()}`);
            if (result.response.ok) await loadParticipants();
        }));
        el('clear').addEventListener('click', () => { 
            setAlert('Escolha uma ação para acompanhar o resultado aqui.', 'neutral');
            setStatus('Pronto');
        });

        setAlert('Escolha uma ação para acompanhar o resultado aqui.', 'neutral');
        run(loadDependencies);

    </script>
</x-app-layout>
