<?php

use AntonioPrimera\Site\Database\ModelBuilders\SectionBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\SiteBuilder;
use Illuminate\Support\Facades\Blade;

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

it('generates a fully functional section component', function () {
    $expectedComponentPath = app_path('View/Components/Sections/HeroSection.php');
    $expectedBladePath = resource_path('views/components/sections/hero-section.blade.php');

    cleanupFiles($expectedComponentPath, $expectedBladePath);

    expect(file_exists($expectedComponentPath))->toBeFalse()
        ->and(file_exists($expectedBladePath))->toBeFalse();

    $this->artisan('site:section', ['name' => 'HeroSection'])
        ->assertExitCode(0);

    expect($expectedBladePath)->toBeFile()
        ->and($expectedComponentPath)->toBeFile();

    //inject some contents into the component files
    file_put_contents($expectedBladePath, '<div>{{ $title }}-{{ $short }}-{{ $contents }}-{{ $icon }}</div>');

    SiteBuilder::create();
    SectionBuilder::createGenericSection('test-hero-section', 'Test Hero Section', 'HSTitle', 'HSShort', 'HSContents')
        ->withData('icon', 'HSIcon');

    include $expectedComponentPath;

    //test the component output
    expect(Blade::renderComponent(new \App\View\Components\Sections\HeroSection('test-hero-section')))
        ->toContain('HSTitle-HSShort-HSContents-HSIcon');
});
