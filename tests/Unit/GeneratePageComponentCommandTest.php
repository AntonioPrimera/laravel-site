<?php

use AntonioPrimera\Site\Database\ModelBuilders\PageBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\SiteBuilder;
use Illuminate\Support\Facades\Blade;

it('can create a page component and the blade file', function () {
	$expectedComponentPath = app_path('View/Components/Pages/AboutUsPage.php');
	$expectedBladePath = resource_path('views/components/pages/about-us-page.blade.php');

	cleanupFiles($expectedComponentPath, $expectedBladePath);

	expect(file_exists($expectedComponentPath))->toBeFalse()
		->and(file_exists($expectedBladePath))->toBeFalse();

	$this->artisan('site:page', ['name' => 'AboutUsPage'])
		->assertExitCode(0);

	expect($expectedBladePath)->toBeFile()
		->and($expectedComponentPath)->toBeFile()
		->and(file_get_contents($expectedComponentPath))
			->toContain(
				'namespace App\View\Components\Pages;',
				'class AboutUsPage extends PageViewComponent'
			)
		->and(file_get_contents($expectedBladePath))
			->toContain(
				'<x-guest-layout>'
			);
});

it('also creates a data migration for the page if the -m flag is set', function () {
    cleanupSiteMigrations();
    expect(migrationExists('create_about_us_page'))->toBeFalse();

    $this->artisan('site:page', ['name' => 'AboutUs', '-m' => true])
        ->assertExitCode(0);

    expect(migrationExists('create_about_us_page'))->toBeTrue();
});

it('generates a fully functional page component', function () {
    $expectedComponentPath = app_path('View/Components/Pages/AboutUsPage.php');
    $expectedBladePath = resource_path('views/components/pages/about-us-page.blade.php');

    cleanupFiles($expectedComponentPath, $expectedBladePath);

    expect(file_exists($expectedComponentPath))->toBeFalse()
        ->and(file_exists($expectedBladePath))->toBeFalse();

    $this->artisan('site:page', ['name' => 'AboutUsPage'])
        ->assertExitCode(0);

    expect($expectedBladePath)->toBeFile()
        ->and($expectedComponentPath)->toBeFile();

    //inject some contents into the component files
    file_put_contents($expectedBladePath, '<div>{{ $title }}-{{ $short }}-{{ $contents }}-{{ $icon }}</div>');

    SiteBuilder::create();
    PageBuilder::create('about-us', 'About Us', 'AUTitle', 'AUShort', 'AUContents')
        ->withData('icon', 'AUIcon');

    file_put_contents($expectedComponentPath, str_replace("//e.g.: return page", "return page", file_get_contents($expectedComponentPath)));

    include $expectedComponentPath;

    //test the component output
    expect(Blade::renderComponent(new \App\View\Components\Pages\AboutUsPage('about-us')))
        ->toContain('AUTitle-AUShort-AUContents-AUIcon');
});
