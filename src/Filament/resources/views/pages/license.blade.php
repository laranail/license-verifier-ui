<x-filament-panels::page>
    @php($info = $this->getLicenseInfo())

    @if (($info['status'] ?? null) === 'valid' || ($info['status'] ?? null) === 'grace')
        <x-filament::section heading="License">
            <dl class="grid grid-cols-2 gap-2">
                <dt>Status</dt><dd>{{ $info['status'] }}</dd>
                @if (!empty($info['licensed_to']))<dt>Licensed to</dt><dd>{{ $info['licensed_to'] }}</dd>@endif
                @if (!empty($info['expires_at']))<dt>Expires</dt><dd>{{ $info['expires_at'] }}</dd>@endif
            </dl>
            <x-filament::button wire:click="deactivate" color="danger" class="mt-4">Deactivate</x-filament::button>
        </x-filament::section>
    @else
        <x-filament::section heading="Activate license">
            <div class="space-y-3">
                <x-filament::input.wrapper>
                    <x-filament::input type="text" wire:model="licenseKey" placeholder="License key" />
                </x-filament::input.wrapper>
                @foreach ($this->getActivationFields() as $field)
                    @if (($field['name'] ?? '') === 'client')
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="client" placeholder="{{ $field['label'] ?? 'Buyer' }}" />
                        </x-filament::input.wrapper>
                    @endif
                @endforeach
                <x-filament::button wire:click="activate">Activate</x-filament::button>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
