<div class="flex items-start max-md:flex-col">
    <div class="flex-1 self-stretch max-md:pt-6">
        <form class="flex flex-col gap-4 mt-5 w-full max-w-lg" wire:submit.prevent="submit" enctype="multipart/form-data">
            <flux:input type="file" wire:model="files" label="Select files to upload" multiple />
            <flux:button class="w-full" variant="primary" wire:click="submit">Upload</flux:button>
        </form>
        @if (session()->has('message'))
            <div>{{ session('message') }}</div>
        @endif
    </div>
</div>
