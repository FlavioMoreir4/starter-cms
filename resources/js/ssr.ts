import { createInertiaApp } from '@inertiajs/vue3';
import { createSSRApp, h } from 'vue';
import type { DefineComponent } from 'vue';

const pages = import.meta.glob<{ default: DefineComponent }>('./pages/**/*.vue', {
    eager: true,
});

createInertiaApp({
    resolve: (name) => {
        const page = pages[`./pages/${name}.vue`];

        if (!page) {
            throw new Error(`Page not found: ${name}`);
        }

        return page;
    },
    setup({ App, props, plugin }) {
        const app = createSSRApp({
            render: () => h(App, props),
        });

        app.use(plugin);

        return app;
    },
});
