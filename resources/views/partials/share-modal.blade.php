<flux:modal name="share-{{ $file->id }}" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Share file</flux:heading>
            <flux:text class="mt-2">Share your files with your friends!</flux:text>
        </div>
        <flux:input label="Valid until" type="date" />
        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">Copy link</flux:button>
        </div>
    </div>
</flux:modal>
