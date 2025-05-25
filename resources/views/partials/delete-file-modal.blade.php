<flux:modal wire:close="resetFileToDelete" name="delete-file" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Delete file</flux:heading>
            <flux:text class="mt-2">Are you sure to delete this file?</flux:text>
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button size="xs" type="submit" variant="danger" wire:click="deleteFile">
                Delete</flux:button>
        </div>
    </div>
</flux:modal>
