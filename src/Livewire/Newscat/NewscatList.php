<?php

namespace Darvis\MantaNews\Livewire\Newscat;


use Darvis\MantaNews\Models\Newscat;
use Darvis\MantaNews\Traits\NewscatTrait;
use Livewire\Component;
use Manta\FluxCMS\Traits\SortableTrait;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class NewscatList extends Component
{
    use NewscatTrait;
    use WithPagination;
    use SortableTrait;
    use MantaTrait;
    use WithSortingTrait;

    public function mount()
    {
        $this->getBreadcrumb('list', [
            'parents' =>
            [
                ['url' => route('news.list'), 'title' => module_config('News')['module_name']['multiple']]
            ]
        ]);
        $this->sortBy = 'sort';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        $this->trashed = count(Newscat::whereNull('pid')->onlyTrashed()->get());

        $obj = Newscat::whereNull('pid');
        if ($this->tablistShow == 'trashed') {
            $obj->onlyTrashed();
        }
        $obj = $this->applySorting($obj);
        $obj = $this->applySearch($obj);
        $items = $obj->paginate(50);
        return view('livewire.manta.newscat.newscat-list', ['items' => $items])->title($this->config['module_name']['multiple']);
    }
}
