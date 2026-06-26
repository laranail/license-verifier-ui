{{-- Compact status badge. Polls the status endpoint on load. --}}
<span class="lv-status-widget" data-lv-status data-lv-status-url="{{ route('license-verifier.status') }}">
    <span class="lv-status-dot" aria-hidden="true"></span>
    <span class="lv-status-label">{{ __('license-verifier::license-verifier.status') }}…</span>
</span>
