<?php

use MadeForYou\News\Blocks\HeroBlock;
use MadeForYou\Helpers\Contracts\ContentBlock;

return [

    /**
     * ## Main category
     *
     * This setting adds a belongs-to relationship from a post to a
     * category. This selected category then becomes the "main"
     * category.
     *
     * @var bool
     */
    'use_main_category' => env('MADE_NEWS_MAIN_CATEGORY', true),

    /**
     * ## Categories
     *
     * This setting creates a polymorphic many-to-many relationship
     * between a post and categories. So it allows you to link a
     * post to multiple categories.
     *
     * @var bool
     */
    'use_categories' => env('MADE_NEWS_CATEGORIES', true),

    /**
     * A list of ContentBlock objects which define which Block components will be added
     * to the content FormBuilder of the news posts.
     *
     * @param array<ContentBlock>
     */
    'content_blocks' => [
        // Add your configured content blocks.
    ],

    /**
     * ## Database
     *
     * Databse settings for changing the column and table names.
     */
    'database' => [

        /**
         * ## Database prefix
         *
         * This prefix will be used when creating the database tables.
         *
         * @var string
         */
        'prefix' => env('MADE_NEWS_DATABASE_PREFIX', 'made'),

    ],

];
