<?php

namespace Darvis\MantaNews\Livewire\Newscat;

use Darvis\MantaNews\Models\Newscat;
use Livewire\Component;
use Darvis\MantaNews\Traits\NewscatTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class NewscatUpload extends Component
{
    use NewscatTrait;

    public function mount(Newscat $newscat)
    {
        $this->item = $newscat;
        $this->itemOrg = $newscat;
        $this->id = $newscat->id;

        $this->getLocaleInfo();
        $this->getTablist();
        $this->getBreadcrumb('upload', [
            'parents' =>
            [
                ['url' => route('news.list'), 'title' => module_config('News')['module_name']['multiple']]
            ]
        ]);
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-upload')->title('Servicescategorie bestanden');
    }
}
