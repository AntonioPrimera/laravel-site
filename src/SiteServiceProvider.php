<?php
namespace AntonioPrimera\Site;

use AntonioPrimera\Site\Commands\GenerateBitComponent;
use AntonioPrimera\Site\Commands\GeneratePageComponent;
use AntonioPrimera\Site\Commands\GenerateSectionComponent;
use AntonioPrimera\Site\Commands\GenerateSiteMigration;
use AntonioPrimera\Site\Commands\GenerateSiteSettings;
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
            ->hasMigrations(['create_sites_table', 'create_pages_table', 'create_sections_table', 'create_bits_table'])
            ->hasCommands([
                GenerateSiteMigration::class,
                GeneratePageComponent::class,
                GenerateSectionComponent::class,
                GenerateBitComponent::class,
                GenerateSiteSettings::class,
            ]);

    }

    public function packageBooted(): void
    {
        //set the path to the data migrations, so that the data migrations are run when calling the 'migrate' command
        $dataMigrationsPath = config('site.data-migrations.path');
        $this->loadMigrationsFrom(database_path($dataMigrationsPath));
    }
}
