<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Teste de Smart Request
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Preencha os blocos, envie o rawText gerado e confirme o evento.
                </p>
            </div>
            <span id="last-status" class="inline-flex w-fit rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                Pronto
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 xl:grid-cols-[minmax(360px,560px)_1fr]">
                <section class="space-y-6">
                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">POST</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Criar smart request</h3>
                            </div>
                            <code class="rounded bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-800">/api/smart-requests</code>
                        </div>

                        <form id="create-form" class="mt-5 space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="event-title">Titulo</label>
                                <input id="event-title" type="text" value="Reuniao de planejamento" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="event-description">Descricao</label>
                                <textarea id="event-description" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">Alinhar prioridades da semana</textarea>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="event-date">Data</label>
                                    <input id="event-date" type="date" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="event-start-time">Hora inicial</label>
                                    <input id="event-start-time" type="time" value="14:00" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="participant-name">Participante</label>
                                    <input id="participant-name" type="text" value="Joao" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700" for="participant-email">E-mail</label>
                                    <input id="participant-email" type="email" value="joao@email.com" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="rounded bg-gray-50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <h4 class="text-sm font-semibold text-gray-900">rawText gerado</h4>
                                    <button id="refresh-text" type="button" class="rounded bg-white px-3 py-1.5 text-xs font-medium text-gray-700 ring-1 ring-gray-300">
                                        Atualizar
                                    </button>
                                </div>
                                <textarea id="raw-text" name="rawText" rows="4" minlength="5" maxlength="1000" required class="mt-3 block w-full rounded border-gray-300 bg-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                <div class="mt-1 flex justify-end text-xs text-gray-500">
                                    <span><span id="char-count">0</span>/1000</span>
                                </div>
                            </div>

                            <div class="rounded bg-gray-50 p-4">
                                <h4 class="text-sm font-semibold text-gray-900">Payload</h4>
                                <pre id="payload-preview" class="mt-3 max-h-48 overflow-auto whitespace-pre-wrap text-xs leading-5 text-gray-700"></pre>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <button type="submit" class="inline-flex justify-center rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-800">
                                    Enviar smart request
                                </button>
                                <button id="reset-example" type="button" class="inline-flex justify-center rounded bg-white px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 hover:bg-gray-50">
                                    Restaurar exemplo
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-600">GET / POST / DELETE</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Gerenciar smart requests</h3>
                            </div>
                            <code class="rounded bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-800">/api/smart-requests</code>
                        </div>

                        <div class="mt-5 grid gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="smart-request-status">Status</label>
                                <select id="smart-request-status" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="pending">pending</option>
                                    <option value="needs_more_info">needs_more_info</option>
                                    <option value="needs_confirmation" selected>needs_confirmation</option>
                                    <option value="suggesting_times">suggesting_times</option>
                                    <option value="confirmed">confirmed</option>
                                    <option value="completed">completed</option>
                                    <option value="cancelled">cancelled</option>
                                    <option value="failed">failed</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="smart-request-id">Smart request</label>
                                <select id="smart-request-id" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Carregando smart requests...</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 rounded bg-amber-50 p-4 text-sm text-amber-900">
                            Para confirmar, o status precisa ser <code>needs_confirmation</code> e seu usuario precisa ter um calendario padrao ativo.
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-3">
                            <button id="list-button" type="button" class="rounded bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800">
                                GET por status
                            </button>
                            <button id="confirm-button" type="button" class="rounded bg-green-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-800">
                                Confirmar
                            </button>
                            <button id="delete-button" type="button" class="rounded bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">
                                DELETE
                            </button>
                        </div>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <h3 class="text-base font-semibold text-gray-900">Dados extraidos</h3>

                        <dl class="mt-4 grid gap-3 text-sm">
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <dt class="font-medium text-gray-500">Status</dt>
                                <dd id="summary-status" class="font-semibold text-gray-900">-</dd>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <dt class="font-medium text-gray-500">Titulo</dt>
                                <dd id="summary-title" class="max-w-[260px] text-right font-semibold text-gray-900">-</dd>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <dt class="font-medium text-gray-500">Inicio</dt>
                                <dd id="summary-start" class="text-right font-semibold text-gray-900">-</dd>
                            </div>
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <dt class="font-medium text-gray-500">Fim</dt>
                                <dd id="summary-end" class="text-right font-semibold text-gray-900">-</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="font-medium text-gray-500">Participantes</dt>
                                <dd id="summary-participants" class="max-w-[260px] text-right font-semibold text-gray-900">-</dd>
                            </div>
                        </dl>
                    </div>
                </section>

                <section class="rounded bg-gray-950 p-5 shadow">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-base font-semibold text-white">Resposta</h3>
                        <button id="clear-response" type="button" class="rounded bg-white px-3 py-1.5 text-sm font-medium text-gray-900">
                            Limpar
                        </button>
                    </div>

                    <pre id="response" class="mt-4 min-h-[680px] overflow-auto whitespace-pre-wrap text-sm leading-6 text-gray-100"></pre>
                </section>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const output = document.getElementById('response');
        const lastStatus = document.getElementById('last-status');
        const rawText = document.getElementById('raw-text');
        const payloadPreview = document.getElementById('payload-preview');
        const smartRequestId = document.getElementById('smart-request-id');
        const smartRequestStatus = document.getElementById('smart-request-status');
        const charCount = document.getElementById('char-count');

        const fields = {
            title: document.getElementById('event-title'),
            description: document.getElementById('event-description'),
            date: document.getElementById('event-date'),
            startTime: document.getElementById('event-start-time'),
            participantName: document.getElementById('participant-name'),
            participantEmail: document.getElementById('participant-email'),
        };

        function tomorrowISODate() {
            const date = new Date();
            date.setDate(date.getDate() + 1);

            return date.toISOString().slice(0, 10);
        }

        function brDate(value) {
            if (!value) {
                return '';
            }

            const [year, month, day] = value.split('-');

            return `${day}/${month}/${year}`;
        }

        function generatedText() {
            const parts = [
                `Marcar ${fields.title.value.trim() || 'evento'}`,
            ];

            if (fields.description.value.trim()) {
                parts.push(`sobre ${fields.description.value.trim()}`);
            }

            if (fields.date.value && fields.startTime.value) {
                parts.push(`em ${brDate(fields.date.value)} as ${fields.startTime.value}`);
            }

            if (fields.participantName.value.trim() && fields.participantEmail.value.trim()) {
                parts.push(`com ${fields.participantName.value.trim()} ${fields.participantEmail.value.trim()}`);
            } else if (fields.participantName.value.trim()) {
                parts.push(`com ${fields.participantName.value.trim()}`);
            }

            return parts.join(' ');
        }

        function payload() {
            return {
                rawText: rawText.value,
            };
        }

        function renderPayloadPreview() {
            rawText.value = generatedText();
            charCount.textContent = rawText.value.length;
            payloadPreview.textContent = JSON.stringify(payload(), null, 2);
        }

        function selectedId() {
            if (!smartRequestId.value) {
                throw new Error('Selecione uma smart request.');
            }

            return smartRequestId.value;
        }

        function renderSmartRequestOptions(smartRequests, preferredId = null) {
            const selectedValue = preferredId ? String(preferredId) : smartRequestId.value;

            smartRequestId.innerHTML = '';

            if (!smartRequests.length) {
                smartRequestId.add(new Option('Nenhuma smart request encontrada', ''));
                smartRequestId.disabled = true;
                showSummary(null);
                return;
            }

            smartRequestId.disabled = false;
            smartRequestId.add(new Option('Selecione uma smart request', ''));

            smartRequests.forEach((smartRequest) => {
                const title = smartRequest.extractedTitle || smartRequest.rawText;
                smartRequestId.add(new Option(
                    `#${smartRequest.id} - ${title}`,
                    smartRequest.id,
                ));
            });

            if ([...smartRequestId.options].some((option) => option.value === selectedValue)) {
                smartRequestId.value = selectedValue;
            }

            showSelectedSummary(smartRequests);
        }

        function showSelectedSummary(smartRequests) {
            const selected = smartRequests.find(
                (smartRequest) => String(smartRequest.id) === smartRequestId.value,
            );

            showSummary(selected ?? null);
        }

        async function loadSmartRequests(preferredId = null) {
            const status = smartRequestStatus.value;
            const response = await fetch(`/api/smart-requests/status/${status}`, {
                headers: {
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data?.message || 'Nao foi possivel carregar as smart requests.');
            }

            renderSmartRequestOptions(data.data ?? [], preferredId);

            return data;
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

        function formatDate(value) {
            if (!value) {
                return '-';
            }

            return new Date(value).toLocaleString();
        }

        function summarizeParticipants(participants) {
            if (!Array.isArray(participants) || participants.length === 0) {
                return '-';
            }

            return participants
                .map((participant) => {
                    if (participant.name && participant.email) {
                        return `${participant.name} (${participant.email})`;
                    }

                    return participant.name || participant.email || JSON.stringify(participant);
                })
                .join(', ');
        }

        function showSummary(data) {
            document.getElementById('summary-status').textContent = data?.status ?? '-';
            document.getElementById('summary-title').textContent = data?.extractedTitle ?? '-';
            document.getElementById('summary-start').textContent = formatDate(data?.extractedStartAt);
            document.getElementById('summary-end').textContent = formatDate(data?.extractedEndAt);
            document.getElementById('summary-participants').textContent = summarizeParticipants(data?.extractedParticipants);
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
                showSummary(data.data);
            }

            if (data?.event_id) {
                setStatus(`Evento criado: ${data.event_id}`, true);
            }

            if (response.ok && method === 'GET' && url.includes('/status/')) {
                renderSmartRequestOptions(data?.data ?? []);
            }

            if (response.ok && method === 'POST' && url === '/api/smart-requests' && data?.data) {
                smartRequestStatus.value = data.data.status;
                await loadSmartRequests(data.data.id);
            }

            if (response.ok && method === 'DELETE') {
                await loadSmartRequests();
            }

            if (response.ok && method === 'POST' && url.endsWith('/confirm')) {
                await loadSmartRequests();
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
            run(() => request('POST', '/api/smart-requests', payload()));
        });

        document.getElementById('confirm-button').addEventListener('click', () => {
            run(() => request('POST', `/api/smart-requests/${selectedId()}/confirm`));
        });

        document.getElementById('list-button').addEventListener('click', () => {
            run(() => request('GET', `/api/smart-requests/status/${smartRequestStatus.value}`));
        });

        document.getElementById('delete-button').addEventListener('click', () => {
            run(() => request('DELETE', `/api/smart-requests/${selectedId()}`));
        });

        smartRequestStatus.addEventListener('change', () => {
            run(loadSmartRequests);
        });

        smartRequestId.addEventListener('change', () => {
            run(loadSmartRequests.bind(null, smartRequestId.value));
        });

        document.getElementById('refresh-text').addEventListener('click', renderPayloadPreview);

        document.getElementById('reset-example').addEventListener('click', () => {
            fields.title.value = 'Reuniao de planejamento';
            fields.description.value = 'Alinhar prioridades da semana';
            fields.date.value = tomorrowISODate();
            fields.startTime.value = '14:00';
            fields.participantName.value = 'Joao';
            fields.participantEmail.value = 'joao@email.com';
            renderPayloadPreview();
        });

        document.getElementById('clear-response').addEventListener('click', () => {
            output.textContent = '';
            setStatus('Pronto');
        });

        Object.values(fields).forEach((field) => {
            field.addEventListener('input', renderPayloadPreview);
            field.addEventListener('change', renderPayloadPreview);
        });

        fields.date.value = tomorrowISODate();
        renderPayloadPreview();
        run(loadSmartRequests);
    </script>
</x-app-layout>
