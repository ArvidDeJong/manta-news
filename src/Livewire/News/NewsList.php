<?php

namespace Darvis\MantaNews\Livewire\News;

use Darvis\MantaNews\Models\News;
use Darvis\MantaNews\Traits\NewsTrait;
use Livewire\Component;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Traits\SortableTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class NewsList extends Component
{
    use NewsTrait;
    use WithPagination;
    use SortableTrait;
    use MantaTrait;
    use WithSortingTrait;

    public function mount()
    {

        $this->getBreadcrumb();
    }

    public function render()
    {
        $this->trashed = count(News::whereNull('pid')->onlyTrashed()->get());

        $obj = News::whereNull('pid');
        if ($this->tablistShow == 'trashed') {
            $obj->onlyTrashed();
        }
        $obj = $this->applySorting($obj);
        $obj = $this->applySearch($obj);
        $items = $obj->paginate(50);
        return view('manta-news::livewire.news.news-list', ['items' => $items])->title($this->config['module_name']['multiple']);
    }
}
