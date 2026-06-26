import LicenseForm from './components/LicenseForm.vue'

/**
 * Register the LicenseForm component with an existing Vue app, e.g.:
 *
 *   import { registerLicenseVerifier } from '.../license-verifier/entry'
 *   registerLicenseVerifier(app)
 */
export function registerLicenseVerifier(app) {
  app.component('LicenseForm', LicenseForm)
}

export { LicenseForm }
export default LicenseForm
