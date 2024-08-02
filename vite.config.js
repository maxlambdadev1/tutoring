import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    build: {
        manifest: true,
        // assetsDir: 'js',
        rtl: true,
        outDir: 'public/build/',
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                assetFileNames: (css) => {
                    if (css.name.split('.').pop() == 'css') {
                        return 'css/' + `[name]` + '.min.' + 'css';
                    } else {
                        return 'icons/' + css.name;
                    }
                },
                entryFileNames: 'js/' + `[name]` + `.js`,
            },
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/tutor.js',
            ],
            refresh: true,
        }),
        // viteStaticCopy({
        //     targets: [
        //         {
        //             src: 'resources/fonts',
        //             dest: ''
        //         },
        //         {
        //             src: 'resources/images',
        //             dest: ''
        //         },
        //         {
        //             src: 'resources/json',
        //             dest: ''
        //         },
        //         {
        //             src: 'resources/libs',
        //             dest: ''
        //         },
        //     ]
        // }),
    ],
});
