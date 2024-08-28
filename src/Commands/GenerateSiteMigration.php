<?php
namespace AntonioPrimera\Site\Commands;

use AntonioPrimera\Artisan\FileGeneratorCommand;
use AntonioPrimera\Artisan\FileRecipes\MigrationRecipe;

class GenerateSiteMigration extends FileGeneratorCommand
{
    protected $signature = 'site:migration {name}';
    protected $description = 'Create a new migration for the site package, creating, updating or deleting SiteComponent models (Sites, Pages, Sections, Bits, or any other models)';

    protected function recipe(): MigrationRecipe
    {
        return (new MigrationRecipe(__DIR__ . '/stubs/site_migration.php.stub'))
            ->withTargetFolder(database_path(config('site.data-migrations.path')));
    }
}
