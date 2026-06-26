{{-- Driver-aware activation form. $fields comes from Driver::activationFields(). --}}
<form
    class="lv-license-form"
    method="POST"
    action="{{ route('license-verifier.activate') }}"
    data-lv-form
>
    @csrf
    @foreach ($fields as $field)
        @php($name = $field['name'] ?? '')
        @continue($name === '')
        <div class="lv-field">
            @if (($field['type'] ?? 'text') === 'checkbox')
                <label>
                    <input type="checkbox" name="{{ $name }}" value="1" @if($field['required'] ?? false) required @endif>
                    {{ $field['label'] ?? $name }}
                </label>
            @else
                <label for="lv-{{ $name }}">{{ $field['label'] ?? $name }}</label>
                <input
                    id="lv-{{ $name }}"
                    type="{{ $field['type'] ?? 'text' }}"
                    name="{{ $name }}"
                    placeholder="{{ $field['placeholder'] ?? '' }}"
                    @if($field['required'] ?? false) required @endif
                >
            @endif
        </div>
    @endforeach

    <div class="lv-actions">
        <button type="submit" class="lv-btn lv-btn-primary">
            {{ __('license-verifier::license-verifier.activate') }}
        </button>
    </div>

    <p class="lv-message" data-lv-message role="status" aria-live="polite"></p>
</form>
