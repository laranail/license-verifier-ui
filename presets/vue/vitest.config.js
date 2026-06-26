import vue from '@vitejs/plugin-vue';
import { defineConfig } from 'vitest/config';

export default defineConfig({
    // The generated SFCs live in stubs as `*.vue.stub`; teach the Vue plugin to
    // compile that extension so the shipped artifacts are tested as-is.
    plugins: [vue({ include: [/\.vue$/, /\.vue\.stub$/] })],
    test: {
        environment: 'jsdom',
        globals: true,
        include: ['tests/js/**/*.test.js'],
    },
});
