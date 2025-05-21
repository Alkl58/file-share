<flux:breadcrumbs class="pl-2">
    <flux:breadcrumbs.item wire:click="goToDirectory('/')" class="cursor-pointer" icon="home" separator="slash">
        Home
    </flux:breadcrumbs.item>

    @php
        $segments = array_filter(explode('/', trim($path, '/')));
        $pathSoFar = '';
    @endphp

    @foreach ($segments as $segment)
        @php
            $pathSoFar .= '/' . $segment;
        @endphp
        <flux:breadcrumbs.item wire:click="goToDirectory('{{ $pathSoFar }}')" class="cursor-pointer" separator="slash">
            {{ $segment }}
        </flux:breadcrumbs.item>
    @endforeach
</flux:breadcrumbs>
