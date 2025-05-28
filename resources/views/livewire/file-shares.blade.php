<div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full text-sm text-left text-zinc-700 dark:text-zinc-300">
        <thead class="bg-zinc-100 dark:bg-zinc-950 text-xs uppercase text-zinc-400 dark:text-zinc-400">
            <tr>
                <th class="px-4 py-3">
                    File / Folder
                </th>

                <th class="px-4 py-3">
                    Created at
                </th>
                <th class="px-4 py-3">
                    Valid until
                </th>
                <th class="px-4 py-3 max-w-2/5">
                    Link
                </th>
                <th class="px-4 py-3 text-right">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($shares as $share)
                <tr class="border-b dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-950">
                    <td class="px-4 py-3">
                        @if ($share->file_id)
                            {{ $share->file->filename }}
                        @else
                            {{ $share->directory->name }}
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        {{ Carbon\Carbon::parse($share->created_at)->diffForHumans() }}
                    </td>
                    <td class="px-4 py-3">
                        {{ Carbon\Carbon::parse($share->valid_until)->diffForHumans() }}
                    </td>
                    <td class="px-4 py-3 max-w-2/5">
                        <flux:input icon="share" value="{{ route('share.index', ['uuid' => $share->code]) }}" readonly
                            copyable />
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end space-x-2">
                            <flux:modal.trigger name="remove-share">
                                <flux:button variant="danger" size="xs"
                                    wire:click="setShareToRemove({{ $share->id }})">Remove</flux:button>
                            </flux:modal.trigger>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">
                        No shares found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <flux:modal wire:close="resetShareToRemove" name="remove-share" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete share?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this share.</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="removeShare">Delete share</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
