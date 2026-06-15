<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Lembretes</h2>
                <p class="mt-1 text-sm text-gray-500">Configure avisos para não perder nenhum compromisso.</p>
            </div>
            <span id="last-status" class="inline-flex w-fit rounded bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">Pronto</span>
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
                            <button id="reload-events" type="button" class="shrink-0 rounded bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Recarregar</button>
                        </div>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <h3 class="text-lg font-semibold text-gray-900">Criar lembrete</h3>
                        <form id="create-form" class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="create-type" class="block text-sm font-medium text-gray-700">Tipo</label>
                                <select id="create-type" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="notification">Notificação</option>
                                    <option value="email">E-mail</option>
                                    <option value="whatsapp">WhatsApp</option>
                                </select>
                            </div>
                            <div>
                                <label for="create-minutes" class="block text-sm font-medium text-gray-700">Minutos antes</label>
                                <input id="create-minutes" type="number" min="0" max="10080" value="30" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </div>
                            <button type="submit" class="w-fit rounded bg-green-700 px-4 py-2 text-sm font-semibold text-white">Salvar lembrete</button>
                        </form>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h3 class="text-lg font-semibold text-gray-900">Lembretes do evento</h3>
                            <button id="list-button" type="button" class="rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white">Atualizar lista</button>
                        </div>
                        <label for="reminder-id" class="mt-4 block text-sm font-medium text-gray-700">Lembrete selecionado</label>
                        <select id="reminder-id" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            <option value="">Liste os lembretes de um evento</option>
                        </select>

                        <div class="mt-4 grid gap-3 sm:grid-cols-3">
                            <button id="show-button" type="button" class="rounded bg-blue-700 px-3 py-2 text-sm font-semibold text-white">Ver detalhes</button>
                            <button id="sent-button" type="button" class="rounded bg-amber-600 px-3 py-2 text-sm font-semibold text-white">Marcar enviado</button>
                            <button id="delete-button" type="button" class="rounded bg-red-700 px-3 py-2 text-sm font-semibold text-white">Excluir</button>
                        </div>
                    </div>

                    <div class="rounded bg-white p-5 shadow">
                        <h3 class="text-lg font-semibold text-gray-900">Atualizar lembrete</h3>
                        <form id="update-form" class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="update-type" class="block text-sm font-medium text-gray-700">Tipo</label>
                                <select id="update-type" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="notification">Notificação</option>
                                    <option value="email">E-mail</option>
                                    <option value="whatsapp">WhatsApp</option>
                                </select>
                            </div>
                            <div>
                                <label for="update-minutes" class="block text-sm font-medium text-gray-700">Minutos antes</label>
                                <input id="update-minutes" type="number" min="0" max="10080" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            </div>
                            <button type="submit" class="w-fit rounded bg-purple-700 px-4 py-2 text-sm font-semibold text-white">Salvar alterações</button>
                        </form>
                    </div>
                </section>

            </div>
        </div>
    </div>


    @vite(['resources/js/event-reminders.js'])

</x-app-layout>
