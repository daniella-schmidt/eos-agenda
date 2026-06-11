
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card Calendários -->
                <a href="{{ route('calendars.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="text-3xl mb-2">📅</div>
                        <h3 class="text-lg font-semibold text-gray-900">Meus Calendários</h3>
                        <p class="text-gray-600 mt-1">Gerencie seus calendários, cores e padrão.</p>
                    </div>
                </a>

                <!-- Card Smart Requests -->
                <a href="{{ route('smart-requests.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="text-3xl mb-2">🤖</div>
                        <h3 class="text-lg font-semibold text-gray-900">Smart Requests</h3>
                        <p class="text-gray-600 mt-1">Solicitações inteligentes de criação de eventos.</p>
                    </div>
                </a>

                <!-- Card Perfil -->
                <a href="{{ route('profile.edit') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="text-3xl mb-2">👤</div>
                        <h3 class="text-lg font-semibold text-gray-900">Meu Perfil</h3>
                        <p class="text-gray-600 mt-1">Atualize suas informações e senha.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>