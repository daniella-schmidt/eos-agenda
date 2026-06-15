<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Sugestões de eventos
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Liste, gere e selecione sugestões vinculadas a uma solicitação.
                </p>
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
                        <h3 class="text-lg font-semibold text-gray-900">Solicitação</h3>

                        <div class="mt-4">
                            <label for="smart-request-id" class="block text-sm font-medium text-gray-700">
                                ID da solicitação
                            </label>
                            <input
                                id="smart-request-id"
                                type="number"
                                min="1"
                                required
                                placeholder="Ex.: 1"
                                class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase text-blue-700">Consulta</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Listar sugestões</h3>
                            </div>
                            <code class="rounded bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-800">
                                /api/smart-requests/{id}/suggestions
                            </code>
                        </div>

                        <button id="list-button" type="button" class="mt-5 rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">
                            Atualizar lista
                        </button>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase text-green-700">Cadastro</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Gerar sugestões</h3>
                            </div>
                            <code class="rounded bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-800">
                                /api/smart-requests/{id}/suggestions/generate
                            </code>
                        </div>

                        <form id="generate-form" class="mt-5 space-y-4">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="days-ahead" class="block text-sm font-medium text-gray-700">
                                        Dias a frente
                                    </label>
                                    <input id="days-ahead" type="number" min="1" max="30" value="7" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>

                                <div>
                                    <label for="limit" class="block text-sm font-medium text-gray-700">
                                        Limite
                                    </label>
                                    <input id="limit" type="number" min="1" max="10" value="3" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>
                            </div>

                            <button type="submit" class="rounded bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-800">
                                Salvar
                            </button>
                        </form>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase text-purple-700">Cadastro</p>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">Selecionar sugestão</h3>
                            </div>
                            <code class="rounded bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-800">
                                /api/event-suggestions/{id}/select
                            </code>
                        </div>

                        <form id="select-form" class="mt-5">
                            <label for="event-suggestion-id" class="block text-sm font-medium text-gray-700">
                                ID da sugestão
                            </label>
                            <div class="mt-1 flex flex-col gap-3 sm:flex-row">
                                <input id="event-suggestion-id" type="number" min="1" required placeholder="Ex.: 1" class="block w-full rounded border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <button type="submit" class="shrink-0 rounded bg-purple-700 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-800">
                                    Selecionar
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="font-semibold text-gray-900">Sugestões retornadas</h3>
                            <span id="suggestion-count" class="rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                0 itens
                            </span>
                        </div>

                        <div id="suggestion-list" class="mt-4 space-y-3">
                            <p class="rounded bg-gray-50 px-4 py-6 text-center text-sm text-gray-500">
                                Liste ou gere sugestões para exibi-las aqui.
                            </p>
                        </div>
                    </div>

                    <div class="rounded bg-gray-900 p-5 shadow">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="font-semibold text-white">Resultado</h3>
                            <button id="clear-output" type="button" class="rounded bg-gray-700 px-3 py-1 text-xs font-medium text-gray-200 hover:bg-gray-600">
                                Limpar
                            </button>
                        </div>
                        <pre id="output" class="mt-4 min-h-80 overflow-auto whitespace-pre-wrap text-sm leading-6 text-green-300">Escolha uma acao para ver o resultado.</pre>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const output = document.getElementById('output');
        const status = document.getElementById('last-status');
        const suggestionList = document.getElementById('suggestion-list');
        const suggestionCount = document.getElementById('suggestion-count');

        function smartRequestId() {
            const id = document.getElementById('smart-request-id').value;

            if (!id) {
                throw new Error('Informe o ID da solicitação.');
            }

            return id;
        }

        function setStatus(text, successful = null) {
            status.textContent = text;
            status.className = 'inline-flex w-fit rounded px-3 py-1 text-xs font-medium';

            if (successful === true) {
                status.classList.add('bg-green-100', 'text-green-800');
            } else if (successful === false) {
                status.classList.add('bg-red-100', 'text-red-800');
            } else {
                status.classList.add('bg-gray-100', 'text-gray-700');
            }
        }

        async function sendRequest(url, options = {}) {
            setStatus('Carregando...');

            try {
                const response = await fetch(url, {
                    ...options,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        ...options.headers,
                    },
                });
                const data = await response.json();

                setStatus(`${response.status} ${response.ok ? 'OK' : 'Erro'}`, response.ok);
                output.textContent = JSON.stringify(data, null, 2);

                if (Array.isArray(data.data)) {
                    renderSuggestions(data.data);
                } else if (data.data?.id) {
                    updateSuggestion(data.data);
                }

                return data;
            } catch (error) {
                setStatus('Erro', false);
                output.textContent = JSON.stringify({ error: error.message }, null, 2);
                throw error;
            }
        }

        function renderSuggestions(suggestions) {
            suggestionCount.textContent = `${suggestions.length} ${suggestions.length === 1 ? 'item' : 'itens'}`;

            if (suggestions.length === 0) {
                suggestionList.innerHTML = '<p class="rounded bg-gray-50 px-4 py-6 text-center text-sm text-gray-500">Nenhuma sugestão encontrada.</p>';
                return;
            }

            suggestionList.innerHTML = '';

            suggestions.forEach((suggestion) => {
                const card = document.createElement('article');
                card.dataset.suggestionId = suggestion.id;
                card.className = `rounded border p-4 ${suggestion.selected ? 'border-green-300 bg-green-50' : 'border-gray-200'}`;

                const header = document.createElement('div');
                header.className = 'flex flex-wrap items-start justify-between gap-3';

                const details = document.createElement('div');
                const title = document.createElement('h4');
                title.className = 'font-semibold text-gray-900';
                title.textContent = `Sugestao #${suggestion.id}`;

                const period = document.createElement('p');
                period.className = 'mt-1 text-sm text-gray-600';
                period.textContent = `${formatDate(suggestion.suggestedStartAt)} ate ${formatDate(suggestion.suggestedEndAt)}`;

                details.append(title, period);

                const badge = document.createElement('span');
                badge.className = `rounded px-2.5 py-1 text-xs font-semibold ${suggestion.selected ? 'bg-green-200 text-green-900' : 'bg-blue-100 text-blue-800'}`;
                badge.textContent = suggestion.selected ? 'Selecionada' : `Score ${suggestion.score}`;
                header.append(details, badge);

                const reason = document.createElement('p');
                reason.className = 'mt-3 text-sm leading-6 text-gray-700';
                reason.textContent = suggestion.reason;

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'mt-4 rounded bg-purple-700 px-3 py-2 text-sm font-semibold text-white hover:bg-purple-800 disabled:cursor-not-allowed disabled:bg-gray-400';
                button.textContent = suggestion.selected ? 'Já selecionada' : 'Selecionar esta sugestão';
                button.disabled = suggestion.selected;
                button.addEventListener('click', () => selectSuggestion(suggestion.id));

                card.append(header, reason, button);
                suggestionList.append(card);
            });
        }

        function updateSuggestion(suggestion) {
            document.getElementById('event-suggestion-id').value = suggestion.id;
            document.querySelectorAll('[data-suggestion-id]').forEach((card) => {
                const selected = Number(card.dataset.suggestionId) === Number(suggestion.id);
                const badge = card.querySelector('span');
                const button = card.querySelector('button');

                card.className = `rounded border p-4 ${selected ? 'border-green-300 bg-green-50' : 'border-gray-200'}`;
                badge.className = `rounded px-2.5 py-1 text-xs font-semibold ${selected ? 'bg-green-200 text-green-900' : 'bg-blue-100 text-blue-800'}`;
                badge.textContent = selected ? 'Selecionada' : badge.textContent.replace('Selecionada', 'Não selecionada');
                button.disabled = selected;
                button.textContent = selected ? 'Já selecionada' : 'Selecionar esta sugestão';
            });
        }

        function formatDate(value) {
            return value
                ? new Intl.DateTimeFormat('pt-BR', {
                    dateStyle: 'short',
                    timeStyle: 'short',
                }).format(new Date(value))
                : '-';
        }

        async function selectSuggestion(id) {
            document.getElementById('event-suggestion-id').value = id;
            await sendRequest(`/api/event-suggestions/${id}/select`, {
                method: 'POST',
                body: JSON.stringify({}),
            });
        }

        document.getElementById('list-button').addEventListener('click', async () => {
            try {
                await sendRequest(`/api/smart-requests/${smartRequestId()}/suggestions`);
            } catch (error) {
                // The response panel already displays request and validation errors.
            }
        });

        document.getElementById('generate-form').addEventListener('submit', async (event) => {
            event.preventDefault();

            try {
                await sendRequest(`/api/smart-requests/${smartRequestId()}/suggestions/generate`, {
                    method: 'POST',
                    body: JSON.stringify({
                        daysAhead: Number(document.getElementById('days-ahead').value),
                        limit: Number(document.getElementById('limit').value),
                    }),
                });
            } catch (error) {
                // The response panel already displays request and validation errors.
            }
        });

        document.getElementById('select-form').addEventListener('submit', async (event) => {
            event.preventDefault();

            const id = document.getElementById('event-suggestion-id').value;

            if (!id) {
                setStatus('Informe uma sugestão', false);
                return;
            }

            try {
                await selectSuggestion(id);
            } catch (error) {
                // The response panel already displays request and validation errors.
            }
        });

        document.getElementById('clear-output').addEventListener('click', () => {
            output.textContent = 'Escolha uma acao para ver o resultado.';
            setStatus('Pronto');
        });
    </script>
</x-app-layout>
