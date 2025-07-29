<?php

namespace Darvis\MantaNews\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Manta\FluxCMS\Traits\HasTranslationsTrait;
use Manta\FluxCMS\Traits\HasUploadsTrait;

class Newscat extends Model
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
        $this->table = config('manta-news.cat.database.table_name');
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
        'active',
        'sort',
        'newscat_id',
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

    /**
     * Get the news items for this category via pivot table
     */
    public function news(): BelongsToMany
    {
        return $this->belongsToMany(News::class, config('manta-news.join.database.table_name'), 'newscat_id', 'news_id')
            ->withTimestamps()
            ->withPivot(['created_by', 'updated_by', 'deleted_by', 'administration', 'identifier', 'data']);
    }

    /**
     * Get the pivot records for this category
     */
    public function newsJoins(): HasMany
    {
        return $this->hasMany(Newscatjoin::class, 'newscat_id');
    }

    /**
     * Get the parent category (for hierarchical categories)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Newscat::class, 'newscat_id');
    }

    /**
     * Get child categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(Newscat::class, 'newscat_id');
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope for categories by locale
     */
    public function scopeByLocale($query, $locale = 'en')
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope for root categories (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('newscat_id');
    }
}
