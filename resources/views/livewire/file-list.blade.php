<div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg overflow-hidden mt-8">
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
            @forelse ($files as $file)
                <tr class="border-b dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-950">
                    <td class="px-4 py-3 flex items-center space-x-2">
                        {{ $file->filename }}
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
                            <flux:button
                                href="{{ route('download.file', ['filePathHash' => $filePathHash, 'fileNameHash' => $fileNameHash]) }}">
                                Download</flux:button>

                            <flux:modal.trigger name="share-{{ $file->id }}">
                                <flux:button>Share</flux:button>
                            </flux:modal.trigger>
                        </div>
                    </td>
                </tr>
                @include('partials.share-modal')
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
