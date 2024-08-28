<?php

namespace AntonioPrimera\Site\Commands;

use AntonioPrimera\Artisan\FileGeneratorCommand;
use AntonioPrimera\Artisan\FileRecipe;
use AntonioPrimera\Artisan\FileRecipes\BladeRecipe;
use AntonioPrimera\Artisan\FileRecipes\ViewComponentRecipe;

class GenerateSectionComponent extends FileGeneratorCommand
{
	protected $signature = 'site:section {name}';
	protected $description = 'Generate a new section component (component class and blade view)';

	protected function recipe(): array|FileRecipe
	{
		return [
			(new ViewComponentRecipe(__DIR__ . '/stubs/SectionComponent.php.stub'))
				->withTargetFolder(app_path(config('site.generator-command.sections.classTargetFolder')))
				->withRootNamespace(config('site.generator-command.sections.rootNamespace')),

			new BladeRecipe(
				__DIR__ . '/stubs/section-component.blade.php.stub',
				config('site.generator-command.sections.bladeTargetFolder')
			)
		];
	}
}
