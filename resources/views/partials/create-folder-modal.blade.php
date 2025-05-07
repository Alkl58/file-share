<flux:modal.trigger name="create-folder">
    <flux:button variant="subtle">Create Folder</flux:button>
</flux:modal.trigger>

<flux:modal name="create-folder" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Crate Folder</flux:heading>
            <flux:text class="mt-2">Please specify a name for your directory.</flux:text>
        </div>

        <flux:input wire:model="newFolderName" label="Name" placeholder="..." />

        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" wire:click="createDirectory" variant="primary">Create</flux:button>
        </div>
    </div>
</flux:modal>
