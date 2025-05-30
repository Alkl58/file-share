<div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg overflow-hidden mt-8">
    <div class="flex items-center p-2">
        @include('partials.folder-breadcrumb')
        <div class="flex w-full justify-end">
            @include('partials.create-folder-modal')
        </div>
    </div>

    <!-- Delete Folder Modal -->
    @include('partials.delete-folder-modal')

    @include('partials.delete-file-modal')

    @include('partials.preview-modal')

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
                    @php
                        $fullPath = trim($path, '/') . '/' . $directory->name;
                        $fullPath = '/' . trim($fullPath, '/');
                    @endphp
                    <td class="px-4 py-3 flex items-center space-x-2 cursor-pointer"
                        wire:click="goToDirectory('{{ $fullPath }}')">
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
                        <flux:dropdown>
                            <flux:button size="xs" icon:trailing="chevron-down">Options</flux:button>

                            <flux:menu>

                                <flux:menu.item icon="share">
                                    Share (Placeholder)
                                </flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item variant="danger" icon="trash"
                                    wire:click="setFolderToDelete({{ $directory->id }})">
                                    Delete
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </td>
                </tr>
                @livewire('share-folder-modal', ['folder_id' => $directory->id])
            @endforeach
            @forelse ($files as $file)
                <tr class="border-b dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-950">
                    <td class="px-4 py-3 flex items-center space-x-2">

                        @if ($file->mime_type == 'image/gif')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-gif">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 8h-2a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2v-4h-1" />
                                <path d="M12 8v8" />
                                <path d="M16 12h3" />
                                <path d="M20 8h-4v8" />
                            </svg>
                        @elseif (str_contains($file->mime_type, 'image'))
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-photo">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M15 8h.01" />
                                <path
                                    d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
                                <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
                            </svg>
                        @elseif (str_contains($file->mime_type, 'video'))
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-movie">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                <path d="M8 4l0 16" />
                                <path d="M16 4l0 16" />
                                <path d="M4 8l4 0" />
                                <path d="M4 16l4 0" />
                                <path d="M4 12l16 0" />
                                <path d="M16 8l4 0" />
                                <path d="M16 16l4 0" />
                            </svg>
                        @elseif ($file->mime_type == 'application/pdf')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-pdf">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" />
                                <path d="M17 18h2" />
                                <path d="M20 15h-3v6" />
                                <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            </svg>
                        @endif
                        <span>
                            @if (str_contains($file->mime_type, 'image'))
                                <a wire:click.prevent="previewFile({{ $file->id }})" class="cursor-pointer">
                                    {{ $file->filename }}
                                </a>
                            @else
                                <span class="cursor-default">
                                    {{ $file->filename }}
                                </span>
                            @endif
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
                        <flux:dropdown>
                            <flux:button size="xs" icon:trailing="chevron-down">Options</flux:button>

                            <flux:menu>
                                <flux:menu.item icon="arrow-down-tray"
                                    href="{{ route('download.file', [
                                        'filePathHash' => $file->getFilePathHash(),
                                        'fileNameHash' => $file->getFileNameHash(),
                                    ]) }}">
                                    Download
                                </flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item icon="share">
                                    Share (Placeholder)
                                </flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item variant="danger" icon="trash"
                                    wire:click="setFileToDelete({{ $file->id }})">
                                    Delete
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
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
