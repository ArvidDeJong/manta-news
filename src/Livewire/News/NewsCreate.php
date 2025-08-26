<?php

namespace Darvis\MantaNews\Livewire\News;

use Darvis\MantaNews\Models\News;
use Darvis\MantaNews\Models\Newscatjoin;
use Darvis\MantaNews\Services\BlogWriterService;
use Darvis\MantaNews\Traits\NewsTrait;
use Manta\FluxCMS\Traits\MantaTrait;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Flux\Flux;
use Manta\FluxCMS\Models\Option;
use Manta\FluxCMS\Services\Openai;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Services\SeoOptimizationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use OpenAI\Exceptions\ErrorException as OpenAiError; // uit de client


#[Layout('manta-cms::layouts.app')]
class NewsCreate extends Component
{
    use MantaTrait;
    use NewsTrait;

    public function mount(Request $request)
    {

        $this->openaiDescription = Option::get('CHATGPT_DESCRIPTION', null, app()->getLocale());

        $this->locale = getLocaleManta();
        if ($request->input('locale') && $request->input('pid')) {
            $item = News::find($request->input('pid'));
            $this->pid = $item->id;
            $this->locale = $request->input('locale');
            $this->itemOrg = $item;
        }

        $this->fields['newscat']['options'] = $this->getNewscats();


        $this->author = auth('staff')->user()->name;

        if (class_exists(Faker::class) && env('APP_ENV') == 'local') {
            $faker = Faker::create('NL_nl');
            $this->title = $faker->sentence(4);
            $this->title_2 = $faker->sentence(4);
            $this->excerpt = $faker->text(500);
            $this->slug = Str::of($this->title)->slug('-');
            $this->seo_title = $this->title;
            $this->content = $faker->text(500);
        }

        $this->getLocaleInfo();
        $this->getTablist();
        $this->getBreadcrumb('create');

        $this->openaiSubject = 'Maak een nieuwsbericht over: ';
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-create')->title($this->config['module_name']['single'] . ' toevoegen');
    }


    public function save()
    {
        $this->validate();


        $row = $this->only(
            'company_id',
            'pid',
            'locale',
            'author',
            'title',
            'title_2',
            'title_3',
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
        $item = News::create($row);

        foreach ($this->newscat as $key => $value) {
            if ($value == true) {
                Newscatjoin::create(['item_id' => $item->id, 'newscat_id' => $key]);
            }
        }

        return $this->redirect(NewsList::class);
    }



    public function getOpenaiResult()
    {
        $svc = app(\Darvis\MantaNews\Services\BlogWriterService::class);

        $result = $svc->generate([
            'topic'   => $this->openaiSubject,
            'audience' => $this->openaiDescription,
            'lang'    => $this->locale ?? 'nl',
        ]);

        $this->title           = $result['title'];
        $this->title_2         = $result['subtitle'];
        $this->title_3         = '';
        $this->slug            = \Illuminate\Support\Str::of($result['title'])->slug('-');
        $this->seo_title       = $result['title'];
        $this->seo_description = strip_tags($result['excerpt']);
        $this->summary         = $result['excerpt'];
        $this->excerpt         = $result['excerpt'];
        $this->content         = $result['content'];


        // Optioneel: direct ook een afbeelding opslaan
        if (!empty($result['image_prompt'])) {
            $img = $svc->generateImageToStorage($result['image_prompt'], '1024x1024');

            $this->openaiImage = $img['url']; // in Livewire alleen de URL bijhouden
        }
    }
}
