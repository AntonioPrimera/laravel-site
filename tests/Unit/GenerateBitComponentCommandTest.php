<?php

use AntonioPrimera\Site\Database\ModelBuilders\BitBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\SiteBuilder;
use Illuminate\Support\Facades\Blade;

it('can create a bit component and the blade file', function () {
	$expectedComponentPath = app_path('View/Components/Bits/ServiceBit.php');
	$expectedBladePath = resource_path('views/components/bits/service-bit.blade.php');

	cleanupFiles($expectedComponentPath, $expectedBladePath);

	expect(file_exists($expectedComponentPath))->toBeFalse()
		->and(file_exists($expectedBladePath))->toBeFalse();

	$this->artisan('site:bit', ['name' => 'ServiceBit'])
		->assertExitCode(0);

	expect($expectedBladePath)->toBeFile()
		->and($expectedComponentPath)->toBeFile()
		->and(file_get_contents($expectedComponentPath))
			->toContain(
				'namespace App\View\Components\Bits;',
				'class ServiceBit extends BitViewComponent'
			)
		->and(file_get_contents($expectedBladePath))
			->toContain(
				'<div>'
			);
});

it('generates a fully functional bit component', function () {
    $expectedComponentPath = app_path('View/Components/Bits/ServiceBit.php');
    $expectedBladePath = resource_path('views/components/bits/service-bit.blade.php');

    cleanupFiles($expectedComponentPath, $expectedBladePath);

    expect(file_exists($expectedComponentPath))->toBeFalse()
        ->and(file_exists($expectedBladePath))->toBeFalse();

    $this->artisan('site:bit', ['name' => 'ServiceBit'])
        ->assertExitCode(0);

    expect($expectedBladePath)->toBeFile()
        ->and($expectedComponentPath)->toBeFile();

    //inject some contents into the component files
    file_put_contents($expectedBladePath, '<div>{{ $title }}-{{ $short }}-{{ $contents }}-{{ $type }}-{{ $icon }}</div>');

    SiteBuilder::create();
    BitBuilder::createGenericBit('test-service-bit', 'Test Service Bit', 'service', 'SBTitle', 'SBShort', 'SBContents')
        ->withData('icon', 'SBIcon');

    include $expectedComponentPath;

    //test the component output
    expect(Blade::renderComponent(new \App\View\Components\Bits\ServiceBit('test-service-bit')))
        ->toContain('SBTitle-SBShort-SBContents-service-SBIcon');
});
