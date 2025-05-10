<div>
    <flux:modal name="share-folder-{{ $folder_id }}" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Share folder</flux:heading>
                <flux:text class="mt-2">Share your folder with your friends!</flux:text>
            </div>
            <flux:input label="Valid until" type="date" wire:model="valid_until" />
            @if ($code)
                <flux:input icon="key" value="{{ route('share.index', ['uuid' => $code]) }}" readonly copyable />
            @endif
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="createShare">Create link</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
