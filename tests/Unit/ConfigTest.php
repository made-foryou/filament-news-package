<?php

it('contains a database prefix value', function () {
    expect(config('filament-news.database.prefix'))->toBe('made');
});
