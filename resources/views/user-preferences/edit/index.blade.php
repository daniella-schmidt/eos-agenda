<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Preferências</h2>
                <p class="mt-1 text-sm text-gray-500">Defina como a agenda deve sugerir e criar seus eventos.</p>
            </div>
            <span id="status" class="rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">Pronto</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <section class="rounded bg-white p-5 shadow">
                <form id="update-form" class="space-y-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="text-sm font-medium text-gray-700">
                            Duração padrão de evento
                            <input name="defaultEventDurationMinutes" type="number" min="5" max="1440" value="60" class="mt-1 block w-full rounded border-gray-300">
                        </label>
                        <label class="text-sm font-medium text-gray-700">
                            Duração padrão de reunião
                            <input name="defaultMeetingDurationMinutes" type="number" min="5" max="480" value="30" class="mt-1 block w-full rounded border-gray-300">
                        </label>
                        <label class="text-sm font-medium text-gray-700">
                            Início preferido
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
                            Exigir confirmação antes de criar eventos
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input name="autoCreateMeetingLink" type="checkbox" class="rounded border-gray-300 text-blue-600">
                            Criar link de reunião automaticamente
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input name="autoCreateReminder" type="checkbox" checked class="rounded border-gray-300 text-blue-600">
                            Criar lembrete automaticamente
                        </label>
                    </div>

                    <div id="message" class="hidden rounded px-4 py-3 text-sm font-semibold"></div>

                    <button type="submit" class="w-full rounded bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
                        Salvar preferências
                    </button>
                </form>
            </section>
        </div>
    </div>

    <script>
        const form = document.getElementById('update-form');
        const status = document.getElementById('status');
        const message = document.getElementById('message');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function showMessage(text, ok = true) {
            message.textContent = text;
            message.className = `rounded px-4 py-3 text-sm font-semibold ${ok ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
            status.textContent = ok ? 'Salvo' : 'Erro';
            status.className = `rounded px-3 py-1 text-xs font-medium ${ok ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            status.textContent = 'Salvando...';
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
                    headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(payload),
                });
                if (!response.ok) throw new Error('Não foi possível salvar suas preferências.');
                showMessage('Preferências salvas com sucesso.');
            } catch (error) {
                showMessage(error.message, false);
            }
        });
    </script>
</x-app-layout>
