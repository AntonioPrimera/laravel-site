<?php

namespace AntonioPrimera\Site\Commands;

use AntonioPrimera\Artisan\FileGeneratorCommand;
use AntonioPrimera\Artisan\FileRecipe;
use AntonioPrimera\Artisan\FileRecipes\BladeRecipe;
use AntonioPrimera\Artisan\FileRecipes\MigrationRecipe;
use AntonioPrimera\Artisan\FileRecipes\ViewComponentRecipe;
use Illuminate\Support\Str;

class GeneratePageComponent extends FileGeneratorCommand
{
	protected $signature = 'site:page {name} {--m|migration}';
	protected $description = 'Generate a new page component (component class and blade view) and optionally a migration if the -m flag is set';

	protected function recipe(): array|FileRecipe
	{
		$componentRecipe = [
			(new ViewComponentRecipe(__DIR__ . '/stubs/PageComponent.php.stub'))
				->withTargetFolder(app_path(config('site.generator-command.pages.classTargetFolder')))
				->withRootNamespace(config('site.generator-command.pages.rootNamespace')),

			new BladeRecipe(
				__DIR__ . '/stubs/page-component.blade.php.stub',
				config('site.generator-command.pages.bladeTargetFolder')
			)
		];

        if ($this->option('migration')) {
            $componentRecipe[] = $this->createMigrationRecipe();
        }

        return $componentRecipe;
	}

    protected function createMigrationRecipe(): MigrationRecipe
    {
        return (new MigrationRecipe(__DIR__ . '/stubs/site_migration.php.stub'))
            ->withTargetFolder(database_path(config('site.data-migrations.path')))
            ->withFileNameTransformer([$this, 'transformMigrationFileName']);
    }

    //prefix the migration file name with the current date and time and 'create_'
    public function transformMigrationFileName(string $fileName): string
    {
        $baseName = Str::snake($fileName);
        if (!str_starts_with($baseName, 'create_'))
            $baseName = 'create_' . $baseName;

        if (!str_ends_with($baseName, '_page'))
            $baseName .= '_page';

        return date('Y_m_d_His') . '_' . $baseName;
    }
}
