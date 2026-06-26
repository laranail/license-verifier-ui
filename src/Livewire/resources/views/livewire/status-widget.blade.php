<span class="lv-status-widget" @class(['is-valid' => $valid, 'is-invalid' => ! $valid])>
    <span class="lv-status-dot" style="background: {{ $valid ? '#34d399' : '#f87171' }}"></span>
    {{ $status->label() }}
</span>
