<?php

namespace Darvis\MantaNews\Livewire\Newscat;

use Darvis\MantaNews\Models\Newscat;
use Manta\FluxCMS\Traits\MantaPagerowTrait;

new class extends \Livewire\Volt\Component {
    public Newscat $item;

    use MantaPagerowTrait;
};
?>
<flux:table.row data-id="{{ $item->id }}">
    <flux:table.cell class="handle"><i class="fa-solid fa-up-down"></i></flux:table.cell>
    <flux:table.cell> <x-manta.tables.image :item="$item->image" /></flux:table.cell>
    @if ($fields['title']['active'])
        <flux:table.cell>{{ $item->title }}</flux:table.cell>
    @endif
    @if ($fields['slug']['active'])
        <flux:table.cell>
            @if ($item->slug && Route::has('website.newscat-item'))
                <a href="{{ route('website.newscat-item', ['slug' => $item->slug]) }}"
                    class="text-blue-500 hover:text-blue-800">{{ $item->slug }}</a>
            @else
                {{ $item->slug }}
            @endif
        </flux:table.cell>
    @endif
    <flux:table.cell>{{ count($item->images) > 0 ? count($item->images) : null }}</flux:table.cell>
    <x-manta.flux.manta-flux-delete :$item :$route_name :$moduleClass uploads :$fields />
</flux:table.row>
