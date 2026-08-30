import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/lib/appearance';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// This will set light / dark mode before the first render...
initializeTheme();

createInertiaApp({
    title: (title) => title || appName,
    progress: {
        color: '#4B5563',
    },
});
