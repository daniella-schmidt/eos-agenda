<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        @vite(['resources/css/contacts.css','resources/js/contacts.js'])

        <div class="contacts-page">
            <div class="mb-5">
                <p class="contacts-eyebrow">Contatos</p>
                <h1 class="contacts-title">Gerencie seus contatos</h1>
            </div>

            <div class="contacts-shell">
                <section class="contacts-card">
                    <div class="contacts-card__header">
                        <p class="contacts-eyebrow">Lista</p>
                        <h2 class="contacts-title">Contatos cadastrados</h2>
                    </div>

                    <div class="contacts-card__body space-y-4">
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <input id="searchInput" class="contacts-search" type="search" placeholder="Buscar por nome, email ou empresa">
                            <button id="searchBtn" class="contacts-btn contacts-btn--ghost" type="button">Buscar</button>
                        </div>

                        <div id="contactsList" class="contacts-list">
                            <div class="contacts-empty">Carregando contatos...</div>
                        </div>
                    </div>
                </section>

                <div class="space-y-5">
                    <section class="contacts-card">
                        <div class="contacts-card__header">
                            <p class="contacts-eyebrow">Cadastro</p>
                            <h2 id="formTitle" class="contacts-title">Novo contato</h2>
                        </div>

                        <div class="contacts-card__body">
                            <form id="contactForm" class="space-y-5">
                                <input id="contactId" type="hidden">

                                <div class="contacts-form-grid">
                                    <div class="contacts-field">
                                        <label for="name">Nome</label>
                                        <input id="name" maxlength="120" required type="text">
                                    </div>

                                    <div class="contacts-field">
                                        <label for="email">E-mail</label>
                                        <input id="email" maxlength="180" type="email">
                                    </div>

                                    <div class="contacts-field">
                                        <label for="phone">Telefone</label>
                                        <input id="phone" maxlength="40" type="text">
                                    </div>

                                    <div class="contacts-field">
                                        <label for="company">Empresa</label>
                                        <input id="company" maxlength="120" type="text">
                                    </div>

                                    <div class="contacts-field contacts-field--full">
                                        <label for="notes">Observacoes</label>
                                        <textarea id="notes" maxlength="1000"></textarea>
                                    </div>
                                </div>

                                <div id="formFeedback" class="contacts-feedback" role="status"></div>

                                <div class="contacts-actions">
                                    <button id="saveBtn" class="contacts-btn contacts-btn--primary" type="submit">Salvar contato</button>
                                    <button id="newBtn" class="contacts-btn contacts-btn--ghost" type="button">Novo</button>
                                    <button id="deleteBtn" class="contacts-btn contacts-btn--danger" type="button" disabled>Excluir</button>
                                </div>
                            </form>
                        </div>
                    </section>

                    <section class="contacts-card">
                        <div class="contacts-card__header">
                            <p class="contacts-eyebrow">Detalhes</p>
                            <h2 class="contacts-title">Contato selecionado</h2>
                        </div>

                        <div class="contacts-card__body">
                            <div id="emptyDetail" class="contacts-empty">
                                Selecione um contato para visualizar seus dados.
                            </div>

                            <div id="contactDetail" class="contact-detail hidden"></div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

