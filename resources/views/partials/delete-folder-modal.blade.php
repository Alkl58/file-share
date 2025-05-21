<flux:modal wire:close="resetFolderToDelete" name="delete-folder" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Delete folder</flux:heading>
            <flux:text class="mt-2">This will delete all of it's contents!</flux:text>
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button size="xs" type="submit" variant="danger" wire:click="deleteDirectory">
                Delete</flux:button>
        </div>
    </div>
</flux:modal>
