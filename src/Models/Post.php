<?php

namespace MadeForYou\News\Models;

use Filament\Facades\Filament;
use MadeForYou\News\Resources\PostResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use MadeForYou\Categories\Models\Category;
use MadeForYou\Categories\Models\WithCategories;
use MadeForYou\News\Database\Factories\PostFactory;
use MadeForYou\News\Exceptions\NoMainCategory;
use MadeForYou\Routes\Contracts\HasRoute;
use MadeForYou\Routes\Models\WithRoute;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
 * @property-read Category|null $category
 * @property-read Collection<Category> $categories
 */
class Post extends Model implements HasMedia, HasRoute
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;
    use WithCategories;
    use WithRoute;

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
     * @throws NoMainCategory
     */
    public function category(): BelongsTo
    {
        if (! config('filament-news.use_main_category')) {
            throw new NoMainCategory(
                'The project does not use a main category or the '
                    . 'categories package is not loaded.'
            );
        }

        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Registers the media collections which the post uses.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('poster')
            ->singleFile()
            ->withResponsiveImages();
    }

    /**
     * Registers conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->queued();
    }

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        $prefix = config('filament-news.database.prefix');

        return $prefix . '_posts';
    }

    #[\Override]
    public function getUrl(): string
    {
        $segments = collect();

        if (config('filament-news.use_main_category')) {

            if ($this->category !== null
                && method_exists($this->category, 'getUrl')
            ) {
                $segments->push($this->category->getUrl());
            }

        }

        $segments->push(Str::slug($this->title));

        return $segments->join('/');
    }

    #[\Override]
    public function getRouteName(): string
    {
        return 'news.' . $this->id;
    }

    #[\Override]
    public function getTitle(): string
    {
        return $this->title;
    }

    #[\Override]
    public function getType(): string
    {
        return 'Nieuwsbericht';
    }

    #[\Override]
    public function getResourceLink(): string
    {
        return PostResource::getUrl('view', ['record' => $this]);
    }


    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }
}
