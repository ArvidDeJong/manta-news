<?php

namespace Darvis\MantaNews\Livewire\Newscat;

use Darvis\MantaNews\Models\Newscat;
use Manta\FluxCMS\Traits\MantaTrait;
use Illuminate\Http\Request;
use Livewire\Component;
use Darvis\MantaNews\Traits\NewscatTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class NewscatRead extends Component
{
    use MantaTrait;
    use NewscatTrait;

    public function mount(Request $request, Newscat $newscat)
    {
        $this->item = $newscat;
        $this->itemOrg = $newscat;
        $this->locale = $newscat->locale;
        if ($request->input('locale') && $request->input('locale') != getLocaleManta()) {
            $this->pid = $newscat->id;
            $this->locale = $request->input('locale');
            $newscat_translate = Newscat::where(['pid' => $newscat->id, 'locale' => $request->input('locale')])->first();
            $this->item = $newscat_translate;
        }

        if ($newscat) {
            $this->id = $newscat->id;
        }
        $this->getLocaleInfo();
        $this->getTablist();
        $this->getBreadcrumb('read', [
            'parents' =>
            [
                ['url' => route('news.list'), 'title' => module_config('News')['module_name']['multiple']]
            ]
        ]);
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-read')->title('Services categorie bekijken');
    }
}
