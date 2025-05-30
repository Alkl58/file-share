<flux:modal name="file-share-modal" wire:close="resetShareModalAttributes" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Share file</flux:heading>
            <flux:text class="mt-2">Share your files with your friends!</flux:text>
        </div>
        <flux:input label="Valid until" type="date" wire:model="shareValidUntil" />
        @if ($shareCode)
            <flux:input icon="key" value="{{ route('share.index', ['uuid' => $shareCode]) }}" readonly copyable />
        @endif
        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary" wire:click="createFileShare">Create link</flux:button>
        </div>
    </div>
</flux:modal>
