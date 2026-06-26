{{-- Mount point for the Vue LicenseForm. Bind it from your Vue app entry. --}}
<div
    id="license-verifier-form"
    data-status-url="{{ route('license-verifier-vue.status') }}"
    data-activate-url="{{ route('license-verifier-vue.activate') }}"
    data-deactivate-url="{{ route('license-verifier-vue.deactivate') }}"
></div>
