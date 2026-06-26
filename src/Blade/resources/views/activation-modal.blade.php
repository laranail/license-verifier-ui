{{-- Drop-in activation modal. Toggle by adding the `is-open` class to [data-lv-modal]. --}}
<div class="lv-modal" data-lv-modal hidden>
    <div class="lv-modal__backdrop" data-lv-modal-close></div>
    <div class="lv-modal__dialog" role="dialog" aria-modal="true" aria-label="{{ __('license-verifier::license-verifier.activation') }}">
        <header class="lv-modal__header">
            <h2>{{ __('license-verifier::license-verifier.activation') }}</h2>
            <button type="button" class="lv-modal__close" data-lv-modal-close aria-label="Close">&times;</button>
        </header>
        <div class="lv-modal__body">
            @include('license-verifier-blade::license-form')
        </div>
    </div>
</div>
