<?php
namespace AntonioPrimera\Site;

use AntonioPrimera\Site\Commands\MakeSiteMigration;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SiteServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-site')
            ->hasConfigFile()
            ->hasMigrations(['create_sections_table', 'create_bits_table'])
            ->runsMigrations()
            ->hasCommands([
                MakeSiteMigration::class,
            ]);

    }

    public function packageBooted(): void
    {
        //set the path to the data migrations, so that the data migrations are run when calling the 'migrate' command
        $dataMigrationsPath = config('site.data-migrations.path');
        $this->loadMigrationsFrom(database_path($dataMigrationsPath));
    }
}
