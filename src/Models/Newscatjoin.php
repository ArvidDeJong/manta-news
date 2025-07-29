<?php

namespace Darvis\MantaNews\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Newscatjoin extends Model
{
    use HasFactory;

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
        $this->table = config('manta-news.join.database.table_name');
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
        'news_id',
        'newscat_id',
        'administration',
        'identifier',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
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
     * Get the news item this pivot belongs to
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    /**
     * Get the category this pivot belongs to
     */
    public function newscat(): BelongsTo
    {
        return $this->belongsTo(Newscat::class, 'newscat_id');
    }

    /**
     * Scope for joins by news item
     */
    public function scopeByNews($query, $newsId)
    {
        return $query->where('news_id', $newsId);
    }

    /**
     * Scope for joins by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('newscat_id', $categoryId);
    }
}
