<?php
namespace AntonioPrimera\Site\Commands;

use AntonioPrimera\Artisan\FileGeneratorCommand;
use AntonioPrimera\Artisan\FileRecipe;
use AntonioPrimera\Artisan\FileRecipes\MigrationRecipe;
use Illuminate\Support\Str;

class GenerateSiteSettings extends FileGeneratorCommand
{
	protected $signature = 'site:settings {name} {--m|migration}';
	protected $description = 'Generate a new class for site settings';

	protected function recipe(): array|FileRecipe
	{
		$componentRecipe = [
			(new FileRecipe(__DIR__ . '/stubs/SiteSettings.php.stub'))
				->withTargetFolder(app_path(config('site.generator-command.settings.classTargetFolder', 'Settings')))
				->withRootNamespace(config('site.generator-command.settings.rootNamespace', 'App\\Settings'))
                ->withFileNameTransformer([$this, 'transformClassName'])
                ->withReplace([
                    'DUMMY_CLASS' => $this->transformClassName(),
                    'SETTINGS_KEY' => $this->settingsKey()
                ]),
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

    public function transformClassName(): string
    {
        //make sure the class is studly cased and ends in 'Settings'
        $name = Str::studly($this->argument('name'));
        return str_ends_with($name, 'Settings') ? $name : "{$name}Settings";
    }

    //prefix the migration file name with the current date and time and 'create_'
    public function transformMigrationFileName(string $fileName): string
    {
        $baseName = Str::snake($fileName);
        if (!str_starts_with($baseName, 'create_'))
            $baseName = 'create_' . $baseName;

        if (!str_ends_with($baseName, '_settings'))
            $baseName .= '_settings';

        return date('Y_m_d_His') . '_' . $baseName;
    }

    protected function settingsKey(): string
    {
        $name = $this->argument('name');
        if (str_ends_with(strtolower($name), 'settings'))
            $name = substr($name, 0, -8);

        return Str::camel(trim($name, ' _-'));
    }
}
