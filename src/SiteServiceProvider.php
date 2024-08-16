<?php

namespace AntonioPrimera\Site;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use AntonioPrimera\Site\Commands\SiteCommand;

class SiteServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-site')
            ->hasConfigFile()
            //->hasViews()
            //->hasCommand(SiteCommand::class)
            ->hasMigrations(['create_sections_table', 'create_bits_table'])
            ->runsMigrations();
    }
}
