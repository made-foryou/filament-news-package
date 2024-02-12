<?php

namespace MadeForYou\News\Tests;

use Filament\FilamentServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use MadeForYou\Categories\FilamentCategoriesServiceProvider;
use MadeForYou\Helpers\HelpersServiceProvider;
use MadeForYou\News\NewsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected array $migrations = [
        '0201012024_create_posts_table',
    ];

    protected array $vendorMigrations = [
        '0101012024_create_categories_table',
        '0201012024_create_categorizables_table',
        'create_routes_table',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'MadeForYou\\News\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function migrate(): void
    {
        foreach ($this->vendorMigrations as $vendorMigration) {

            $vendorMigrationFilePath = __DIR__ . '/migrations/'
                . $vendorMigration . '.php.stub';

            if (! file_exists($vendorMigrationFilePath)) {
                continue;
            }

            $migration = include $vendorMigrationFilePath;

            $migration->up();

        }

        foreach ($this->migrations as $migration) {

            if (! file_exists($this->getMigrationPath($migration))) {
                continue;
            }

            $migration = include $this->getMigrationPath($migration);

            // Run the migration
            $migration->up();

        }
    }

    protected function getMigrationPath(string $migrationFile): string
    {
        return __DIR__ . '/../database/migrations/' . $migrationFile
            . '.php.stub';
    }

    protected function getPackageProviders($app): array
    {
        return [
            MediaLibraryServiceProvider::class,
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentCategoriesServiceProvider::class,
            NewsServiceProvider::class,
            HelpersServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('filament-categories.database.prefix', 'made');

        $this->migrate();
    }
}
