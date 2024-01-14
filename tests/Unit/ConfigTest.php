<?php

it('contains a database prefix value', function () {
    expect(config('filament-news.database.prefix'))->toBe('made');
});

it('contains a use_main_category setting', function () {
    expect(config('filament-news.use_main_category'))->toBe(true);
});

it('contains a use_categories setting', function () {
    expect(config('filament-news.use_categories'))->toBe(true);
});
