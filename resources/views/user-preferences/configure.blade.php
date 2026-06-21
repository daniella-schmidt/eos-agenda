<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        @vite(['resources/css/user-preferences-configure.css', 'resources/js/user-preferences-configure.js'])

        <div class="pref-page">
            <section class="pref-card">
                <div class="pref-toolbar">
                    <div>
                        <p class="pref-subtitle">Preferências do usuário</p>
                        <h2 class="pref-title">Configurações</h2>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                        <span class="method-badge">GET /api/user-preferences</span>
                        <button id="send-request" type="button" class="btn-action btn-primary">
                            Consultar preferências
                        </button>
                        <a href="{{ route('user-preferences.update-tester') }}" class="btn-action">
                            Atualizar preferências
                        </a>
                    </div>
                </div>

                <div class="pref-layout">
                    <div id="prefs-display">
                        <div class="empty-state">
                            Clique em "Consultar preferências" para carregar os dados.
                        </div>
                    </div>

                    <div style="background:#0f1923;border:1px solid #1e3040;border-radius:8px;padding:18px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:16px;">
                            <h3 style="font-weight:900;color:#ffffff;font-size:.9rem;">Resposta da API</h3>
                            <span id="status" style="background:#1e3040;color:#94b3b3;border-radius:4px;padding:3px 10px;font-size:.75rem;font-weight:700;">Pronto</span>
                        </div>
                        <pre id="output" style="min-height:380px;overflow:auto;white-space:pre-wrap;font-size:.82rem;line-height:1.7;color:#4ade80;">Clique em "Consultar preferências".</pre>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>

