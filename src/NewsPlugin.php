<?php

namespace MadeForYou\News;

use Filament\Panel;
use Filament\Contracts\Plugin;

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
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }   
}