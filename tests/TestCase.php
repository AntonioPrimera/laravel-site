<?php

namespace AntonioPrimera\Site\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use AntonioPrimera\Site\SiteServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'AntonioPrimera\\Site\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            MediaLibraryServiceProvider::class,
            SiteServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        //run the media library migration
        $mediaMigration = include __DIR__ . '/../vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub';
        $mediaMigration->up();

        //run the package migrations
        foreach (['create_sites_table', 'create_pages_table', 'create_sections_table', 'create_bits_table'] as $migrationName) {
            $migration = include __DIR__."/../database/migrations/{$migrationName}.php";
            $migration->up();
        }

        //$migration = include __DIR__.'/../database/migrations/create_laravel-site_table.php.stub';
        //$migration->up();
    }
}
