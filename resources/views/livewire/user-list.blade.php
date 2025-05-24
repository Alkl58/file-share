<div>
    <div class="relative pt-5 text-gray-900 dark:text-white w-[100%]" wire:keydown.right.window="nextPage"
        wire:keydown.left.window="previousPage">
        <div class="flex items-center justify-center">
            <div class="relative overflow-x-auto rounded-lg w-[100%]">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-white">
                    <thead class="text-xs text-white uppercase bg-gray-50 dark:bg-pink-700 dark:text-neutral-200 ">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Created at
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Used Space
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr wire:key="user-{{ $user->id }}"
                                class="bg-neutral-950/50 border-t dark:bg-neutral-800 dark:border-pink-700">
                                <td class="px-6 py-4">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-x-3 whitespace-nowrap">
                                        <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                                            role="progressbar" aria-valuenow="{{ $user->getUserUsedSpacePercentage() }}"
                                            aria-valuemin="0" aria-valuemax="{{ $user->space_limit }}">
                                            <div class="flex flex-col justify-center rounded-full overflow-hidden bg-zinc-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-zinc-500"
                                                style="width: {{ $user->getUserUsedSpacePercentage() }}%"></div>
                                        </div>
                                        <div class="w-10 text-end">
                                            <span
                                                class="text-sm text-gray-800 dark:text-white">{{ $user->getUserUsedSpacePercentage() }}%</span>
                                        </div>
                                    </div>

                                </td>
                                <td class="px-6 py-4">
                                    <flux:modal.trigger name="change-contingent">
                                        <flux:button size="xs" wire:click="setUser({{ $user->id }})">Change
                                            Contingent</flux:button>
                                    </flux:modal.trigger>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $users->links() }}
    </div>
    <flux:modal wire:close="resetUserID" name="change-contingent" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Update profile</flux:heading>
                <flux:text class="mt-2">Make changes to your personal details.</flux:text>
            </div>
            <flux:input label="Space in bytes" wire:model="currentContingent" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="updateContingent">Save changes</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
