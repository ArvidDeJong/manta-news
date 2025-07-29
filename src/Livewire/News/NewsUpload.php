<?php

namespace Darvis\MantaNews\Livewire\News;

use Livewire\Component;
use Darvis\MantaNews\Models\News;
use Manta\FluxCMS\Traits\MantaTrait;
use Darvis\MantaNews\Traits\NewsTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class NewsUpload extends Component
{
    use MantaTrait;
    use NewsTrait;

    public function mount(News $news)
    {
        $this->item = $news;
        $this->itemOrg = $news;
        $this->id = $news->id;
        $this->locale = $news->locale;

        $this->getLocaleInfo();
        $this->getTablist();
        $this->getBreadcrumb('upload');
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-upload')->title($this->config['module_name']['single'] . ' bestanden');
    }
}
