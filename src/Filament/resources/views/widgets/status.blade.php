<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-2">
            <span class="inline-block h-2.5 w-2.5 rounded-full" style="background: {{ $valid ? '#22c55e' : '#ef4444' }}"></span>
            <span class="text-sm font-medium">License: {{ $label }}</span>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
