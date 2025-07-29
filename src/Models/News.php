<?php

namespace Darvis\MantaNews\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Manta\FluxCMS\Traits\HasUploadsTrait;
use Manta\FluxCMS\Traits\HasTranslationsTrait;

class News extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUploadsTrait;
    use HasTranslationsTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('manta-news.database.table_name');
        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'company_id',
        'host',
        'pid',
        'locale',
        'author',
        'active',
        'sort',
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
        'administration',
        'identifier',
        'data',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'active' => 'boolean',
        'deleted_at' => 'datetime',
    ];



    /**
     * Boot de model events voor het automatisch vastleggen van gebruikersinformatie.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($item) {
            $user = Auth::guard('staff')->user();
            if ($user) {
                $item->created_by = $user->name;
            }
        });

        static::updating(function ($item) {
            $user = Auth::guard('staff')->user();
            if ($user) {
                $item->updated_by = $user->name;
            }
        });

        static::deleting(function ($item) {
            $user = Auth::guard('staff')->user();
            if ($user) {
                $item->deleted_by = $user->name;
            }
        });
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    public array $upload_cats = ['datasheet' => 'News datasheet'];

    /**
     * Get the categories for this news item via pivot table
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Newscat::class, config('manta-news.join.database.table_name'), 'news_id', 'newscat_id')
            ->withTimestamps()
            ->withPivot(['created_by', 'updated_by', 'deleted_by', 'administration', 'identifier', 'data']);
    }

    /**
     * Get the pivot records for this news item
     */
    public function categoryJoins(): HasMany
    {
        return $this->hasMany(Newscatjoin::class, 'news_id');
    }

    /**
     * Get translations for this news item
     */
    public function translations(): HasMany
    {
        return $this->hasMany(News::class, 'pid', 'id');
    }

    /**
     * Get the parent news item (for translations)
     */
    public function parent()
    {
        return $this->belongsTo(News::class, 'pid');
    }

    /**
     * Get a list of category titles for this news item
     */
    public function getCategoriesList(): array
    {
        return $this->categories->pluck('title')->toArray();
    }

    /**
     * Scope for active news items
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope for news items by locale
     */
    public function scopeByLocale($query, $locale = 'en')
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope for news items by company
     */
    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
