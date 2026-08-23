<x-filament-widgets::widget>
    <div x-data="{ defaultCvIsUndefined: @js($this->defaultCvIsUndefined()) }"
        x-on:default-cv-updated.window="defaultCvIsUndefined = ! $event.detail.defaultIsSet"
        x-show="defaultCvIsUndefined"
    >
        <x-filament::callout
            icon="heroicon-o-exclamation-triangle"
            color="warning"
        >
            <x-slot name="heading">
                No default CV
            </x-slot>
            <x-slot name="description">
                The homepage will appear empty.
            </x-slot>
        </x-filament::callout>
    </div>
</x-filament-widgets::widget>
