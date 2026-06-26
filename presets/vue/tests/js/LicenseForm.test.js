import { flushPromises, mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import Bootstrap from '../../stubs/themes/bootstrap/js/components/LicenseForm.vue.stub';
import Custom from '../../stubs/themes/custom/js/components/LicenseForm.vue.stub';
import Tailwind from '../../stubs/themes/tailwind/js/components/LicenseForm.vue.stub';
import Unstyled from '../../stubs/themes/unstyled/js/components/LicenseForm.vue.stub';

const themes = {
    tailwind: Tailwind,
    bootstrap: Bootstrap,
    unstyled: Unstyled,
    custom: Custom,
};

const props = {
    statusUrl: '/license/status',
    activateUrl: '/license/activate',
    deactivateUrl: '/license/deactivate',
    csrf: 'test-token',
};

/** Queue fetch() responses in call order. */
function mockFetch(...responses) {
    const fn = vi.fn();
    responses.forEach((r) =>
        fn.mockResolvedValueOnce({ ok: r.ok ?? true, json: async () => r.body }));
    global.fetch = fn;

    return fn;
}

const unlicensed = {
    ok: true,
    body: { valid: false, fields: [{ name: 'license_key', label: 'License key', type: 'text' }] },
};

describe.each(Object.entries(themes))('LicenseForm (%s theme)', (_name, Component) => {
    beforeEach(() => vi.restoreAllMocks());

    it('renders the activation form when unlicensed', async () => {
        mockFetch(unlicensed);

        const wrapper = mount(Component, { props });
        await flushPromises();

        expect(wrapper.find('form').exists()).toBe(true);
        expect(wrapper.find('input').exists()).toBe(true);
    });

    it('renders the licensed state when already valid', async () => {
        mockFetch({ ok: true, body: { valid: true, fields: [] } });

        const wrapper = mount(Component, { props });
        await flushPromises();

        expect(wrapper.find('form').exists()).toBe(false);
        expect(wrapper.text()).toContain('active');
    });

    it('activates: POSTs the form with CSRF and flips to licensed', async () => {
        const fetch = mockFetch(unlicensed, { ok: true, body: { message: 'Activated', data: { valid: true } } });

        const wrapper = mount(Component, { props });
        await flushPromises();

        await wrapper.find('input').setValue('DEV-KEY');
        await wrapper.find('form').trigger('submit');
        await flushPromises();

        expect(fetch).toHaveBeenCalledTimes(2);
        const [url, options] = fetch.mock.calls[1];
        expect(url).toBe('/license/activate');
        expect(options.method).toBe('POST');
        expect(options.headers['X-CSRF-TOKEN']).toBe('test-token');
        expect(JSON.parse(options.body).license_key).toBe('DEV-KEY');
        expect(wrapper.find('form').exists()).toBe(false);
    });

    it('deactivates: POSTs to the deactivate endpoint and returns to the form', async () => {
        const fetch = mockFetch({ ok: true, body: { valid: true, fields: [] } }, { ok: true, body: {} });

        const wrapper = mount(Component, { props });
        await flushPromises();

        await wrapper.find('button').trigger('click');
        await flushPromises();

        expect(fetch.mock.calls[1][0]).toBe('/license/deactivate');
        expect(wrapper.find('form').exists()).toBe(true);
    });
});
