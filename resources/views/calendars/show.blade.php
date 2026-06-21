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

    @vite(['resources/css/calendars.css', 'resources/js/calendars.js'])

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

                <div id="calendar-events" class="mt-5 space-y-4" data-calendar-id="{{ (int) $calendar->id }}"></div>
            </section>
        </div>
    </div>

    @vite(['resources/js/calendars-show.js'])
</x-app-layout>

