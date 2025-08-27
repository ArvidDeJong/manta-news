<?php

namespace Darvis\MantaNews\Traits;

use Darvis\MantaNews\Models\News;
use Darvis\MantaNews\Models\Newscat;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Manta\FluxCMS\Models\MantaModule;
use Manta\FluxCMS\Services\ModuleSettingsService;

trait NewsTrait
{
    public function __construct()
    {
        $this->module_routes = [
            'name' => 'news',
            'list' => 'news.list',
            'create' => 'news.create',
            'update' => 'news.update',
            'read' => 'news.read',
            'upload' => 'news.upload',
            'settings' => 'news.settings',
            'maps' => null,
        ];

        $settings = ModuleSettingsService::ensureModuleSettings('news', 'darvis/manta-news');
        $this->config = $settings;

        $this->fields = $settings['fields'] ?? [];
        $this->tab_title = $settings['tab_title'] ?? null;
        $this->moduleClass = 'Darvis\MantaNews\Models\News';
        $this->openaiImagePossible = true;
    }

    // * Model items
    public ?News $item = null;
    public ?News $itemOrg = null;



    #[Locked]
    public ?string $company_id = null;

    #[Locked]
    public ?string $host = null;

    public ?string $locale = null;
    public ?string $pid = null;

    public ?string $author = null;
    public ?string $title = null;
    public ?string $title_2 = null;
    public ?string $title_3 = null;

    public ?string $seo_title = null;
    public ?string $seo_description = null;
    public ?string $tags = null;
    public ?string $summary = null;
    public ?string $excerpt = null;
    public ?string $content = null;

    public array $newscat = [];

    #[Locked]
    public ?string $redirect_url = null;

    public function rules()
    {
        $return = [];
        if ($this->fields['title']) $return['title'] = 'required';
        // if ($this->fields['excerpt']) $return['excerpt'] = 'required';

        if (isset($this->fields['slug']) && $this->fields['slug']['active'] == true) {
            if ($this->item) {
                $return['slug'] = $this->fields['slug']['required'] == true ? 'required|string|max:255|unique:news,slug,' . $this->item->id : 'nullable|string|max:255|unique:news,slug';
            } else {
                $return['slug'] = $this->fields['slug']['required'] == true ? 'required|string|max:255|unique:news,slug' : 'nullable|string|max:255|unique:news,slug';
            }
        }

        return $return;
    }

    public function messages()
    {
        $return = [];
        $return['title.required'] = 'De titel is verplicht';
        $return['excerpt.required'] = 'De inleiding is verplicht';
        return $return;
    }

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query->where(function (Builder $querysub) {
                $querysub->where('title', 'LIKE', "%{$this->search}%")
                    ->orWhere('excerpt', 'LIKE', "%{$this->search}%")
                    ->orWhere('content', 'LIKE', "%{$this->search}%");
            });
    }
    public function getNewscats()
    {
        $return = [];

        foreach (Newscat::whereNull('newscat_id')->whereNull('pid')->get() as $value) {
            $return[$value->id] = $value->title;
        }

        return $return;
    }
}
