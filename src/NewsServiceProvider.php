<?php

namespace MadeForYou\News;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NewsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-news';

    public function configurePackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasConfigFile()
            ->hasMigrations(['0201012024_create_posts_table'])
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->startWith(function (InstallCommand $command) {
                    $command->info('Let\'s install the package');
                })
                    ->publishMigrations()
                    ->publishConfigFile();
            });
    }
}
