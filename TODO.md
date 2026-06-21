# TODO - Separar CSS e JS das views

- [x] Ajustar `resources/views/events/index.blade.php`: deixar apenas `@vite(['resources/css/events.css','resources/js/events.js'])` (remover scripts inline).

- [x] Criar `resources/css/calendars.css` com o CSS que estava inline em `resources/views/calendars/index.blade.php`.
- [x] Criar `resources/js/calendars.js` com o JS que estava inline em `resources/views/calendars/index.blade.php`.
- [x] Ajustar `resources/views/calendars/index.blade.php`: remover `<style>` e `<script>` inline e trocar por `@vite(['resources/css/calendars.css','resources/js/calendars.js'])` + mount root.
- [ ] Validar páginas `/events` e `/calendars` (abrir no browser) e corrigir eventuais dependências de DOM (data-* ids/classes).

