<div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg overflow-hidden mt-8">
    <div class="flex items-center p-2">
        @include('partials.folder-breadcrumb')
        <div class="flex w-full justify-end">
            @include('partials.create-folder-modal')
        </div>
    </div>
    <table class="min-w-full text-sm text-left text-zinc-700 dark:text-zinc-300">
        <thead class="bg-zinc-100 dark:bg-zinc-950 text-xs uppercase text-zinc-400 dark:text-zinc-400">
            <tr>
                <th class="px-4 py-3">
                    Name
                </th>
                <th class="px-4 py-3">
                    Size
                </th>
                <th class="px-4 py-3">
                    Created
                </th>
                <th class="px-4 py-3 text-right">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($directories as $directory)
                <tr wire:key="dir-{{ $directory->id }}"
                    class="hover:bg-gray-100 dark:hover:bg-zinc-950 bg-zinc-100/40 dark:bg-zinc-950/70">
                    <td class="px-4 py-3 flex items-center space-x-2 cursor-pointer"
                        wire:click="goToDirectory({{ $directory->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-folder">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />
                        </svg>
                        <span>{{ $directory->name }}</span>
                    </td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3 text-right">
                        <!-- placeholder -->
                    </td>
                </tr>
            @endforeach
            @forelse ($files as $file)
                <tr class="border-b dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-950">
                    <td class="px-4 py-3 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        </svg>
                        <span>
                            {{ $file->filename }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $bytes = $file->file_size;
                            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                            $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
                            $file_size = round($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
                        @endphp
                        {{ $file_size }}
                    </td>
                    <td class="px-4 py-3">
                        {{ Carbon\Carbon::parse($file->created_at)->diffForHumans() }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end space-x-2">
                            @php
                                $fileNameHash = \Illuminate\Support\Facades\Crypt::encryptString($file->filename);
                                $filePathHash = \Illuminate\Support\Facades\Crypt::encryptString($file->path);
                            @endphp
                            <flux:button size="xs"
                                href="{{ route('download.file', ['filePathHash' => $filePathHash, 'fileNameHash' => $fileNameHash]) }}">
                                Download</flux:button>

                            <flux:modal.trigger name="share-{{ $file->id }}">
                                <flux:button size="xs">Share</flux:button>
                            </flux:modal.trigger>
                        </div>
                    </td>
                </tr>
                @livewire('share-modal', ['file_id' => $file->id])
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">
                        No files found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
