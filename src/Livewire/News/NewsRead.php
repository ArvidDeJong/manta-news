<?php

namespace Darvis\MantaNews\Livewire\News;

use Darvis\MantaNews\Models\News;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Component;
use Illuminate\Http\Request;
use Darvis\MantaNews\Traits\NewsTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class NewsRead extends Component
{
    use MantaTrait;
    use NewsTrait;

    public function mount(Request $request, News $news)
    {
        $this->item = $news;
        $this->itemOrg = $news;
        $this->locale = $news->locale;
        if ($request->input('locale') && $request->input('locale') != getLocaleManta()) {
            $this->pid = $news->id;
            $this->locale = $request->input('locale');
            $item_translate = News::where(['pid' => $news->id, 'locale' => $request->input('locale')])->first();
            $this->item = $item_translate;
        }

        $this->fields['newscat']['value'] = $this->itemOrg->getCategoriesList();

        if ($news) {
            $this->id = $news->id;
        }
        $this->getLocaleInfo();
        $this->getBreadcrumb('read');
        $this->getTablist();
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-read')->title($this->config['module_name']['single'] . ' bekijken');
    }
}
