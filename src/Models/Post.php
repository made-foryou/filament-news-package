<?php

namespace MadeForYou\News\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use MadeForYou\Categories\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use MadeForYou\Categories\Models\WithCategories;

/**
 * ## Post model
 * ---
 *
 * @property-read int $id
 * @property string $title
 * @property Carbon $date
 * @property string $summary
 * @property mixed $content
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property ?Carbon $deleted_at
 * @property-read Collection<Category> $categories
 */
class Post extends Model
{
    use WithCategories;

    /**
     * The table associated with the model.
     */
    protected string $table = 'posts';

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        $prefix = config('filament-news.database.prefix');

        return $prefix . '_' . parent::getTable();
    }
}
