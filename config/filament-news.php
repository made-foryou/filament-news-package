<?php

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
