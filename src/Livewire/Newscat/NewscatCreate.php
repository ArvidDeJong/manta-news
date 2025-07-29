<?php

namespace Darvis\MantaNews\Livewire\Newscat;

use Darvis\MantaNews\Models\Newscat;
use Manta\FluxCMS\Traits\MantaTrait;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Str;
use Darvis\MantaNews\Traits\NewscatTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class NewscatCreate extends Component
{
    use MantaTrait;
    use NewscatTrait;

    public function mount(Request $request)
    {
        $this->locale = getLocaleManta();
        if ($request->input('locale') && $request->input('pid')) {
            $newscat = Newscat::find($request->input('pid'));
            $this->pid = $newscat->id;
            $this->locale = $request->input('locale');
            $this->itemOrg = $newscat;
        }


        $this->getLocaleInfo();
        $this->getTablist();
        $this->getBreadcrumb('create', [
            'parents' =>
            [
                ['url' => route('news.list'), 'title' => module_config('News')['module_name']['multiple']]
            ]
        ]);
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-create')->title('Servicescategorie toevoegen');
    }

    public function save()
    {
        $this->validate();

        $row = $this->only(
            'company_id',
            'pid',
            'locale',
            'title',
            'title_2',
            'slug',
            'seo_title',
            'seo_description',
            'tags',
            'summary',
            'excerpt',
            'content',
        );
        $row['created_by'] = auth('staff')->user()->name;
        $row['host'] = request()->host();
        $row['slug'] = $this->slug ? $this->slug : Str::of($this->title)->slug('-');
        Newscat::create($row);
        // $this->toastr('success', 'News toegevoegd');

        return $this->redirect(NewscatList::class);
    }
}
