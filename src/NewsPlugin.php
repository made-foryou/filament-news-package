<?php

namespace MadeForYou\News;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MadeForYou\News\Resources\PostResource;

class NewsPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return NewsServiceProvider::$name;
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            PostResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
