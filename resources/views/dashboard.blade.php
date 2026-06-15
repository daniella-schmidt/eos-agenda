<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
                <p class="mt-1 text-sm text-gray-500">Acesse rapidamente os principais fluxos da sua agenda.</p>
            </div>
            <a href="{{ route('events.page') }}" class="rounded bg-white px-4 py-2 text-sm font-semibold text-gray-800 ring-1 ring-gray-300">
                Novo evento
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                <a href="{{ route('events.page') }}" class="block bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-black uppercase text-teal-700">Agenda</div>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Eventos</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-600">Crie, acompanhe, edite, cancele e remova compromissos.</p>
                </a>

                <a href="{{ route('calendars.index') }}" class="block bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-black uppercase text-teal-700">Organização</div>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Calendários</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-600">Separe eventos por cor, contexto e calendário padrão.</p>
                </a>

                <a href="{{ route('smart-requests.index') }}" class="block bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-black uppercase text-teal-700">Criação rápida</div>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Solicitações inteligentes</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-600">Escreva o que precisa agendar e revise antes de confirmar.</p>
                </a>

                <a href="{{ route('contacts.page') }}" class="block bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-black uppercase text-teal-700">Pessoas</div>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Contatos</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-600">Mantenha nomes, e-mails e telefones prontos para eventos.</p>
                </a>

                <a href="{{ route('event-reminders.page') }}" class="block bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-black uppercase text-teal-700">Avisos</div>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Lembretes</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-600">Configure alertas para não perder compromissos importantes.</p>
                </a>

                <a href="{{ route('event-participants.page') }}" class="block bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="text-sm font-black uppercase text-teal-700">Convidados</div>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Participantes</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-600">Gerencie convidados, papéis e respostas dos eventos.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
