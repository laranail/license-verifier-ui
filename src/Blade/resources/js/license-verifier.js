/**
 * Dependency-free license-verifier UI handler.
 * Reproduces the Botble Vue/jQuery behaviour with plain fetch + a small
 * localStorage TTL cache (3 days verified / 1 day unverified).
 */
(function () {
  'use strict'

  const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
  const TTL_VERIFIED = 3 * 24 * 60 * 60 * 1000
  const TTL_UNVERIFIED = 24 * 60 * 60 * 1000

  function setMessage(form, text, ok) {
    const el = form.querySelector('[data-lv-message]')
    if (!el) return
    el.textContent = text || ''
    el.classList.toggle('is-error', ok === false)
    el.classList.toggle('is-success', ok === true)
  }

  async function submit(form) {
    const data = new FormData(form)
    const res = await fetch(form.getAttribute('action'), {
      method: form.getAttribute('method') || 'POST',
      headers: { 'X-CSRF-TOKEN': csrf(), Accept: 'application/json' },
      body: data,
    })
    let payload = {}
    try { payload = await res.json() } catch (e) { /* noop */ }
    return { ok: res.ok, payload }
  }

  function onSubmit(e) {
    const form = e.target.closest('[data-lv-form]')
    if (!form) return
    e.preventDefault()
    const btn = form.querySelector('button[type=submit]')
    if (btn) btn.disabled = true

    submit(form)
      .then(({ ok, payload }) => {
        setMessage(form, payload.message || (ok ? 'Done.' : 'Failed.'), ok)
        try { localStorage.removeItem('lv_status_cache') } catch (e) {}
        if (ok && form.hasAttribute('data-lv-reload')) {
          setTimeout(() => window.location.reload(), 800)
        } else if (ok) {
          const redirect = form.getAttribute('data-lv-redirect')
          if (redirect) setTimeout(() => window.location.assign(redirect), 800)
        }
      })
      .finally(() => { if (btn) btn.disabled = false })
  }

  function cachedStatus() {
    try {
      const raw = JSON.parse(localStorage.getItem('lv_status_cache') || 'null')
      if (raw && raw.expires > Date.now()) return raw.data
    } catch (e) {}
    return null
  }

  function renderStatus(node, data) {
    const valid = data && (data.status === 'valid' || data.status === 'grace')
    const label = node.querySelector('.lv-status-label')
    const dot = node.querySelector('.lv-status-dot')
    if (label) label.textContent = data ? data.status : 'unknown'
    if (dot) dot.style.background = valid ? '#34d399' : '#f87171'
  }

  function initStatus() {
    document.querySelectorAll('[data-lv-status]').forEach((node) => {
      const cached = cachedStatus()
      if (cached) { renderStatus(node, cached); return }
      fetch(node.getAttribute('data-lv-status-url'), { headers: { Accept: 'application/json' } })
        .then((r) => r.json().then((p) => ({ r, p })))
        .then(({ r, p }) => {
          const data = p.data || p
          renderStatus(node, data)
          const ttl = r.ok ? TTL_VERIFIED : TTL_UNVERIFIED
          try { localStorage.setItem('lv_status_cache', JSON.stringify({ data, expires: Date.now() + ttl })) } catch (e) {}
        })
        .catch(() => {})
    })
  }

  function initModal() {
    document.addEventListener('click', (e) => {
      if (e.target.closest('[data-lv-modal-open]')) {
        document.querySelector('[data-lv-modal]')?.removeAttribute('hidden')
      }
      if (e.target.closest('[data-lv-modal-close]')) {
        document.querySelector('[data-lv-modal]')?.setAttribute('hidden', '')
      }
    })
  }

  document.addEventListener('submit', onSubmit)
  document.addEventListener('DOMContentLoaded', () => { initStatus(); initModal() })
})()
