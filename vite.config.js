import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/eos-app.css',
                'resources/js/app.js',
                'resources/css/eos-components.css',
                'resources/css/calendars.css',
                'resources/css/smart-requests.css',
                'resources/js/calendars.js',
                'resources/js/smart-requests.js',
                'resources/js/event-reminders.js',

            ],
            refresh: true,
        }),
    ],
});
