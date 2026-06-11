<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Teste de consulta das preferencias
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Consulta ou cria as preferencias do usuario autenticado.
                </p>
            </div>
            <a href="{{ route('user-preferences.update-tester') }}" class="rounded bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-gray-300">
                Testar atualizacao
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto grid max-w-5xl gap-6 px-4 sm:px-6 lg:grid-cols-[320px_1fr] lg:px-8">
            <section class="rounded bg-white p-5 shadow">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">GET</p>
                <code class="mt-2 block rounded bg-blue-50 px-3 py-2 text-sm text-blue-800">/api/user-preferences</code>

                <button id="send-request" type="button" class="mt-5 w-full rounded bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">
                    Consultar preferencias
                </button>
            </section>

            <section class="rounded bg-gray-900 p-5 shadow">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-white">Resposta</h3>
                    <span id="status" class="rounded bg-gray-700 px-3 py-1 text-xs font-medium text-gray-200">Pronto</span>
                </div>
                <pre id="output" class="mt-4 min-h-80 overflow-auto whitespace-pre-wrap text-sm leading-6 text-green-300">Clique em "Consultar preferencias".</pre>
            </section>
        </div>
    </div>

    <script>
        const output = document.getElementById('output');
        const status = document.getElementById('status');

        document.getElementById('send-request').addEventListener('click', async () => {
            status.textContent = 'Carregando...';

            try {
                const response = await fetch('/api/user-preferences', {
                    headers: {
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();

                status.textContent = `${response.status} ${response.ok ? 'OK' : 'Erro'}`;
                output.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                status.textContent = 'Erro';
                output.textContent = JSON.stringify({ error: error.message }, null, 2);
            }
        });
    </script>
</x-app-layout>
