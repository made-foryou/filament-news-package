<?php

it('contains a database prefix value', function () {
    expect(config('filament-news.database_prefix'))->toBe('made');
});

it('contains a posts table name', function () {
    expect(config('filament-news.posts_table_name'))->toBe('posts');
});

it('contains a categories table name', function () {
    expect(config('filament-news.categories_table_name'))->toBe('categories');
});
