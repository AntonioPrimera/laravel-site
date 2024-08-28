<?php

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
