<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $calendar->name }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $calendar->description ?: 'Eventos vinculados a este calendário.' }}</p>
            </div>
            <a href="{{ route('calendars.index') }}" class="rounded bg-white px-4 py-2 text-sm font-semibold text-gray-800 ring-1 ring-gray-300">Voltar</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <section class="rounded bg-white p-5 shadow">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="h-8 w-8 rounded-full border-2 border-gray-900" style="background: {{ $calendar->color ?? '#008f91' }}"></span>
                    @if ($calendar->isDefault)
                        <span class="rounded bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-900">Calendário padrão</span>
                    @endif
                    @unless ($calendar->isActive)
                        <span class="rounded bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">Inativo</span>
                    @endunless
                </div>
            </section>

            <section class="mt-6 rounded bg-white p-5 shadow">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase text-blue-700">Agenda</p>
                        <h3 class="mt-1 text-lg font-semibold text-gray-900">Eventos deste calendário</h3>
                    </div>
                    <span id="status" class="rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">Carregando...</span>
                </div>

                <div id="calendar-events" class="mt-5 space-y-4"></div>
            </section>
        </div>
    </div>

    <script>
        const container = document.getElementById('calendar-events');
        const statusBadge = document.getElementById('status');
        const statusLabels = { draft: 'Rascunho', confirmed: 'Confirmado', cancelled: 'Cancelado' };
        const priorityLabels = { low: 'Baixa', medium: 'Média', high: 'Alta' };

        function formatDate(value) {
            return value ? new Intl.DateTimeFormat('pt-BR', { dateStyle: 'short', timeStyle: 'short' }).format(new Date(value)) : 'Sem data';
        }

        function escapeHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        async function loadEvents() {
            try {
                const response = await fetch('/api/calendars/{{ $calendar->id }}/events', { headers: { Accept: 'application/json' } });
                const data = await response.json();
                if (!response.ok) throw new Error(data?.message || 'Não foi possível carregar os eventos.');
                const events = data.data ?? [];
                statusBadge.textContent = `${events.length} ${events.length === 1 ? 'evento' : 'eventos'}`;

                if (!events.length) {
                    container.innerHTML = '<div class="rounded bg-gray-50 px-4 py-8 text-center text-sm text-gray-600">Nenhum evento neste calendário ainda.</div>';
                    return;
                }

                container.innerHTML = events.map((event) => `
                    <article class="rounded border border-gray-200 p-4">
                        <h4 class="font-semibold text-gray-900">${escapeHtml(event.title)}</h4>
                        <p class="mt-1 text-sm text-gray-600">${formatDate(event.startAt)} até ${formatDate(event.endAt)}</p>
                        ${event.description ? `<p class="mt-3 text-sm leading-6 text-gray-700">${escapeHtml(event.description)}</p>` : ''}
                        <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold">
                            <span class="rounded bg-blue-50 px-2.5 py-1 text-blue-800">${statusLabels[event.status] ?? event.status}</span>
                            <span class="rounded bg-gray-100 px-2.5 py-1 text-gray-700">Prioridade ${priorityLabels[event.priority] ?? event.priority}</span>
                        </div>
                    </article>
                `).join('');
            } catch (error) {
                statusBadge.textContent = 'Erro';
                container.innerHTML = `<div class="rounded bg-red-50 px-4 py-6 text-sm text-red-800">${escapeHtml(error.message)}</div>`;
            }
        }

        loadEvents();
    </script>
</x-app-layout>
