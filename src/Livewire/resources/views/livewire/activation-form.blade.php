<div class="lv-livewire-form">
    @if ($activated)
        <p class="lv-success">{{ $message }}</p>
    @else
        <form wire:submit="activate">
            @foreach ($fields as $field)
                @php($name = $field['name'] ?? '')
                @continue($name === '')
                <div class="lv-field">
                    @if (($field['type'] ?? 'text') === 'checkbox')
                        <label><input type="checkbox" wire:model="agreement"> {{ $field['label'] ?? $name }}</label>
                    @elseif ($name === 'client')
                        <label>{{ $field['label'] ?? 'Buyer' }}</label>
                        <input type="text" wire:model="client">
                    @else
                        <label>{{ $field['label'] ?? $name }}</label>
                        <input type="{{ $field['type'] ?? 'text' }}" wire:model="licenseKey" placeholder="{{ $field['placeholder'] ?? '' }}">
                    @endif
                    @error($name === 'license_key' ? 'licenseKey' : $name) <span class="lv-error">{{ $message }}</span> @enderror
                </div>
            @endforeach

            <button type="submit">{{ __('license-verifier::license-verifier.activate') }}</button>
            @if ($message) <p class="lv-error">{{ $message }}</p> @endif
        </form>
    @endif
</div>
