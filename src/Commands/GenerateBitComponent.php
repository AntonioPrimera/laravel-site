<?php

namespace AntonioPrimera\Site\Commands;

use AntonioPrimera\Artisan\FileGeneratorCommand;
use AntonioPrimera\Artisan\FileRecipe;
use AntonioPrimera\Artisan\FileRecipes\BladeRecipe;
use AntonioPrimera\Artisan\FileRecipes\ViewComponentRecipe;

class GenerateBitComponent extends FileGeneratorCommand
{
	protected $signature = 'site:bit {name}';
	protected $description = 'Generate a new bit component (component class and blade view)';

	protected function recipe(): array|FileRecipe
	{
		return [
			(new ViewComponentRecipe(__DIR__ . '/stubs/BitComponent.php.stub'))
				->withTargetFolder(app_path(config('site.generator-command.bits.classTargetFolder')))
				->withRootNamespace(config('site.generator-command.bits.rootNamespace')),

			new BladeRecipe(
				__DIR__ . '/stubs/bit-component.blade.php.stub',
				config('site.generator-command.bits.bladeTargetFolder')
			)
		];
	}
}
