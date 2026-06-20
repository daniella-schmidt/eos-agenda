<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Minhas preferências</h2>
                <p class="mt-1 text-sm text-gray-500">Ajuste os padrões usados pela agenda ao criar eventos.</p>
            </div>
            <a href="{{ route('user-preferences.edit-page') }}" class="rounded bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-gray-300">
                Editar preferências
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <section class="rounded bg-white p-6 text-sm leading-6 text-gray-700 shadow">
                <h3 class="text-lg font-semibold text-gray-900">Configurações da agenda</h3>
                <p class="mt-3">Use a tela de edição para definir duração padrão, horário preferido, intervalo entre eventos, confirmação antes da criação e lembretes automáticos.</p>
                <a href="{{ route('user-preferences.edit-page') }}" class="mt-5 inline-flex rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white">
                    Abrir preferências
                </a>
            </section>
        </div>
    </div>
</x-app-layout>
