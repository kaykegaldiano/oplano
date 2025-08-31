<x-filament::button
    size="sm"
    x-data="{}"
    x-on:click="$dispatch('open-modal', { id: 'quick-note-modal' })"
    class="ml-2"
>
    {{ __('Quick Note') }}
</x-filament::button>

<x-filament::modal id="quick-note-modal" width="md">
    <x-slot name="heading">{{ __('New Observation') }}</x-slot>
    <livewire:quick-note/>
</x-filament::modal>
