<flux:main container>
    <x-manta.breadcrumb :$breadcrumb />
    <div class="mt-4 flex">
        <div class="flex-grow">
            <x-manta.buttons.large type="add" :href="route($this->route_name . '.create')" />
        </div>
        <div class="w-1/5">
            <x-manta.input.search />
        </div>
    </div>
    <x-manta.tables.tabs :$tablistShow :$trashed />
    <flux:table :paginate="$items">
        <flux:table.columns>
            <flux:table.column></flux:table.column>
            @if ($fields['title']['active'])
                <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                    wire:click="dosort('title')">
                    Titel</flux:table.column>
            @endif
            @if ($fields['slug']['active'])
                <flux:table.column sortable :sorted="$sortBy === 'slug'" :direction="$sortDirection"
                    wire:click="dosort('slug')">
                    Slug</flux:table.column>
            @endif
        </flux:table.columns>
        <flux:table.rows id="draggable">
            @foreach ($items as $item)
                <livewire:manta.newscat.newscat-list-row :$fields :$item :route_name="$this->route_name" :$moduleClass
                    :key="$item->id">
            @endforeach
        </flux:table.rows>
    </flux:table>
    <x-manta.draggable />
</flux:main>
