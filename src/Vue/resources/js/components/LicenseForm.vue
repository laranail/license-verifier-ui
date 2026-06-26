<template>
  <div class="lv-license-form">
    <div v-if="loading" class="lv-loading">…</div>

    <div v-else-if="verified" class="lv-licensed">
      <p>{{ license.status }} — {{ license.licensed_to }}</p>
      <button type="button" @click="deactivate">{{ t.deactivate }}</button>
    </div>

    <form v-else @submit.prevent="activate">
      <div v-for="field in fields" :key="field.name" class="lv-field">
        <label v-if="field.type !== 'checkbox'">{{ field.label }}</label>
        <input
          v-if="field.type !== 'checkbox'"
          :type="field.type || 'text'"
          :placeholder="field.placeholder || ''"
          v-model="form[field.name]"
        />
        <label v-else><input type="checkbox" v-model="form[field.name]" /> {{ field.label }}</label>
      </div>
      <button type="submit" :disabled="submitting">{{ t.activate }}</button>
      <p v-if="message" class="lv-message" :class="{ 'is-error': !ok }">{{ message }}</p>
    </form>
  </div>
</template>

<script>
export default {
  name: 'LicenseForm',
  props: {
    statusUrl: { type: String, required: true },
    activateUrl: { type: String, required: true },
    deactivateUrl: { type: String, required: true },
    labels: { type: Object, default: () => ({ activate: 'Activate license', deactivate: 'Deactivate' }) },
  },
  data() {
    return { loading: true, submitting: false, verified: false, license: {}, fields: [], form: {}, message: '', ok: true }
  },
  computed: { t() { return this.labels } },
  mounted() { this.fetchStatus() },
  methods: {
    csrf() { return document.querySelector('meta[name="csrf-token"]')?.content || '' },
    async fetchStatus() {
      this.loading = true
      try {
        const res = await fetch(this.statusUrl, { headers: { Accept: 'application/json' } })
        const payload = await res.json()
        this.verified = !!payload.valid
        this.license = (payload.data) || {}
        this.fields = payload.fields || [{ name: 'license_key', label: 'License key', type: 'text' }]
      } finally { this.loading = false }
    },
    async activate() {
      this.submitting = true
      try {
        const res = await fetch(this.activateUrl, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': this.csrf(), 'Content-Type': 'application/json', Accept: 'application/json' },
          body: JSON.stringify(this.form),
        })
        const payload = await res.json()
        this.ok = res.ok
        this.message = payload.message || ''
        if (res.ok) { this.verified = true; this.license = payload.data || {} }
      } finally { this.submitting = false }
    },
    async deactivate() {
      const res = await fetch(this.deactivateUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': this.csrf(), Accept: 'application/json' },
      })
      if (res.ok) { this.verified = false; this.form = {} }
    },
  },
}
</script>
