<flux:breadcrumbs class="pl-2">
    <flux:breadcrumbs.item wire:click="goToDirectory(null)" class="cursor-pointer" icon="home" separator="slash">
        Home
    </flux:breadcrumbs.item>
    @foreach ($this->breadcrumbs as $crumb)
        <flux:breadcrumbs.item wire:click="goToDirectory({{ $crumb->id }})" class="cursor-pointer" separator="slash">
            {{ $crumb->name }}
        </flux:breadcrumbs.item>
    @endforeach
</flux:breadcrumbs>
