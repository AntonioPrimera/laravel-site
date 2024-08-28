<?php

it('can create a section component and the blade file', function () {
	$expectedComponentPath = app_path('View/Components/Sections/HeroSection.php');
	$expectedBladePath = resource_path('views/components/sections/hero-section.blade.php');

	cleanupFiles($expectedComponentPath, $expectedBladePath);

	expect(file_exists($expectedComponentPath))->toBeFalse()
		->and(file_exists($expectedBladePath))->toBeFalse();

	$this->artisan('site:section', ['name' => 'HeroSection'])
		->assertExitCode(0);

	expect($expectedBladePath)->toBeFile()
		->and($expectedComponentPath)->toBeFile()
		->and(file_get_contents($expectedComponentPath))
			->toContain(
				'namespace App\View\Components\Sections;',
				'class HeroSection extends SectionViewComponent'
			)
		->and(file_get_contents($expectedBladePath))
			->toContain(
				'<div>'
			);
});
