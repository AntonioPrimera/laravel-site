<?php

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
