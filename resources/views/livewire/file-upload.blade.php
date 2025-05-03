<div>
    <form wire:submit.prevent="submit" enctype="multipart/form-data">
        <input type="file" wire:model="files" multiple>
        @error('file')
            <span class="error">{{ $message }}</span>
        @enderror

        <flux:button class="w-full" variant="primary" wire:click="submit">Upload</flux:button>
    </form>

    @if (session()->has('message'))
        <div>{{ session('message') }}</div>
    @endif
</div>
