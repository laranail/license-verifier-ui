import { readFileSync } from 'node:fs';

import { JSDOM } from 'jsdom';
import { describe, expect, it, vi } from 'vitest';

/** Read a theme's shipped script stub (tokens live only in comments → valid JS). */
function script(theme) {
    return readFileSync(`stubs/themes/${theme}/js/license-verifier.js.stub`, 'utf8');
}

/** Build an isolated JSDOM window, mock fetch, and eval the script into it. */
function boot(theme, bodyHtml = '') {
    const dom = new JSDOM(
        `<!DOCTYPE html><html><head><meta name="csrf-token" content="tok"></head><body>${bodyHtml}</body></html>`,
        { runScripts: 'dangerously', url: 'http://localhost' },
    );

    const fetchMock = vi.fn(() =>
        Promise.resolve({ ok: true, json: () => Promise.resolve({ message: 'OK' }) }));
    dom.window.fetch = fetchMock;
    dom.window.eval(script(theme));

    return { dom, fetchMock };
}

const tick = () => new Promise((resolve) => setTimeout(resolve, 0));

// The IIFE themes share a contract: delegate-submit [data-lv-form] via fetch.
describe.each(['tailwind', 'bootstrap', 'unstyled', 'custom'])('license-verifier.js (%s)', (theme) => {
    it('intercepts [data-lv-form] submit and POSTs via fetch with the CSRF token', async () => {
        const { dom, fetchMock } = boot(
            theme,
            '<form data-lv-form action="/license/activate" method="post">'
            + '<input name="license_key" value="DEV-KEY"><span data-lv-message></span>'
            + '<button type="submit">go</button></form>',
        );

        const form = dom.window.document.querySelector('[data-lv-form]');
        form.dispatchEvent(new dom.window.Event('submit', { bubbles: true, cancelable: true }));
        await tick();

        expect(fetchMock).toHaveBeenCalledTimes(1);
        const [url, options] = fetchMock.mock.calls[0];
        expect(url).toBe('/license/activate');
        expect(options.method).toBe('POST');
        expect(options.headers['X-CSRF-TOKEN']).toBe('tok');
    });

    it('does not hijack submits outside [data-lv-form]', async () => {
        const { dom, fetchMock } = boot(theme, '<form id="other" action="/x"><button type="submit">go</button></form>');

        dom.window.document.querySelector('#other')
            .dispatchEvent(new dom.window.Event('submit', { bubbles: true, cancelable: true }));
        await tick();

        expect(fetchMock).not.toHaveBeenCalled();
    });
});

// The Alpine theme registers a component instead of binding listeners directly.
describe('license-verifier.js (alpine)', () => {
    function bootAlpine(bodyHtml = '') {
        const dom = new JSDOM(
            `<!DOCTYPE html><html><head><meta name="csrf-token" content="tok"></head><body>${bodyHtml}</body></html>`,
            { runScripts: 'dangerously', url: 'http://localhost' },
        );
        const dataSpy = vi.fn();
        dom.window.Alpine = { data: dataSpy };
        dom.window.eval(script('alpine'));
        dom.window.document.dispatchEvent(new dom.window.Event('alpine:init'));

        return { dom, factory: dataSpy.mock.calls[0]?.[1], dataSpy };
    }

    it('registers the lvLicenseForm component on alpine:init', () => {
        const { dataSpy } = bootAlpine();

        expect(dataSpy).toHaveBeenCalledWith('lvLicenseForm', expect.any(Function));
    });

    it('activate() POSTs to the form action with the CSRF token and sets ok', async () => {
        const { dom, factory } = bootAlpine('<form action="/license/activate" method="post"></form>');
        dom.window.fetch = vi.fn(() =>
            Promise.resolve({ ok: true, json: () => Promise.resolve({ message: 'OK' }) }));

        const component = factory();
        component.$el = dom.window.document.querySelector('form');
        await component.activate();

        expect(dom.window.fetch).toHaveBeenCalledTimes(1);
        const [url, options] = dom.window.fetch.mock.calls[0];
        expect(url).toBe('/license/activate');
        expect(options.headers['X-CSRF-TOKEN']).toBe('tok');
        expect(component.ok).toBe(true);
    });
});
