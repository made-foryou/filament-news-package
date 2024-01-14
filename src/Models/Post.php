<?php

namespace MadeForYou\News\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use MadeForYou\Categories\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use MadeForYou\Categories\Models\WithCategories;
use MadeForYou\News\Exceptions\NoMainCategory;

/**
 * ## Post model
 * ---
 *
 * @property-read int $id
 * @property int|null $category_id
 * @property string $title
 * @property Carbon $date
 * @property string $summary
 * @property mixed $content
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property ?Carbon $deleted_at
 *
 * @property-read Category|null $category
 * @property-read Collection<Category> $categories
 */
class Post extends Model
{
    use WithCategories;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'date',
        'summary',
        'content',
    ];

    /**
     * Casting settings for the properties of this model.
     */
    protected $casts = [
        'date' => 'date',
        'content' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Retrieves and returns the main category of the message.
     */
    public function category(): BelongsTo
    {
        if (!config('filament-news.use_main_category')) {
            throw new NoMainCategory(
                'The project does not use a main category or the '
                    . 'categories package is not loaded.'
            );
        }

        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        $prefix = config('filament-news.database.prefix');

        return $prefix . '_posts';
    }
}
