<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Teste de atualizacao das preferencias
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Envia uma atualizacao parcial para o usuario autenticado.
                </p>
            </div>
            <a href="{{ route('user-preferences.show-tester') }}" class="rounded bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-gray-300">
                Testar consulta
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <section class="rounded bg-white p-5 shadow">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">PATCH</p>
                    <code class="rounded bg-amber-50 px-3 py-1.5 text-xs text-amber-800">/api/user-preferences</code>
                </div>

                <form id="update-form" class="mt-5 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="text-sm font-medium text-gray-700">
                            Duracao de evento
                            <input name="defaultEventDurationMinutes" type="number" min="5" max="1440" value="60" class="mt-1 block w-full rounded border-gray-300">
                        </label>
                        <label class="text-sm font-medium text-gray-700">
                            Duracao de reuniao
                            <input name="defaultMeetingDurationMinutes" type="number" min="5" max="480" value="30" class="mt-1 block w-full rounded border-gray-300">
                        </label>
                        <label class="text-sm font-medium text-gray-700">
                            Inicio preferido
                            <input name="preferredStartTime" type="time" value="09:00" class="mt-1 block w-full rounded border-gray-300">
                        </label>
                        <label class="text-sm font-medium text-gray-700">
                            Fim preferido
                            <input name="preferredEndTime" type="time" value="18:00" class="mt-1 block w-full rounded border-gray-300">
                        </label>
                    </div>

                    <label class="block text-sm font-medium text-gray-700">
                        Intervalo entre eventos
                        <input name="bufferBetweenEventsMinutes" type="number" min="0" max="180" value="15" class="mt-1 block w-full rounded border-gray-300">
                    </label>

                    <div class="space-y-3 rounded bg-gray-50 p-4">
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input name="requireConfirmationBeforeCreate" type="checkbox" checked class="rounded border-gray-300 text-blue-600">
                            Exigir confirmacao antes de criar
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input name="autoCreateMeetingLink" type="checkbox" class="rounded border-gray-300 text-blue-600">
                            Criar link de reuniao automaticamente
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input name="autoCreateReminder" type="checkbox" checked class="rounded border-gray-300 text-blue-600">
                            Criar lembrete automaticamente
                        </label>
                    </div>

                    <button type="submit" class="w-full rounded bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
                        Atualizar preferencias
                    </button>
                </form>
            </section>

            <section class="rounded bg-gray-900 p-5 shadow">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-white">Resposta</h3>
                    <span id="status" class="rounded bg-gray-700 px-3 py-1 text-xs font-medium text-gray-200">Pronto</span>
                </div>
                <pre id="output" class="mt-4 min-h-96 overflow-auto whitespace-pre-wrap text-sm leading-6 text-green-300">Preencha o formulario e envie.</pre>
            </section>
        </div>
    </div>

    <script>
        const form = document.getElementById('update-form');
        const output = document.getElementById('output');
        const status = document.getElementById('status');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            status.textContent = 'Enviando...';

            const values = new FormData(form);
            const payload = {
                defaultEventDurationMinutes: Number(values.get('defaultEventDurationMinutes')),
                defaultMeetingDurationMinutes: Number(values.get('defaultMeetingDurationMinutes')),
                preferredStartTime: values.get('preferredStartTime'),
                preferredEndTime: values.get('preferredEndTime'),
                bufferBetweenEventsMinutes: Number(values.get('bufferBetweenEventsMinutes')),
                requireConfirmationBeforeCreate: values.has('requireConfirmationBeforeCreate'),
                autoCreateMeetingLink: values.has('autoCreateMeetingLink'),
                autoCreateReminder: values.has('autoCreateReminder'),
            };

            try {
                const response = await fetch('/api/user-preferences', {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload),
                });
                const data = await response.json();

                status.textContent = `${response.status} ${response.ok ? 'OK' : 'Erro'}`;
                output.textContent = JSON.stringify({
                    request: payload,
                    response: data,
                }, null, 2);
            } catch (error) {
                status.textContent = 'Erro';
                output.textContent = JSON.stringify({ error: error.message }, null, 2);
            }
        });
    </script>
</x-app-layout>
