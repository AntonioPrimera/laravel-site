<?php

namespace AntonioPrimera\Site;

use AntonioPrimera\Site\Commands\SiteCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
