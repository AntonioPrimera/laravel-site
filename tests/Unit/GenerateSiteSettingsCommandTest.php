<?php

it('can create a site settings class', function () {
    $expectedPath = app_path('Settings/SocialMediaSettings.php');
    cleanupFiles($expectedPath);

    expect(file_exists($expectedPath))->toBeFalse();

    $this->artisan('site:settings', ['name' => 'SocialMediaSettings'])
        ->assertExitCode(0);

    expect($expectedPath)->toBeFile()
        ->and(file_get_contents($expectedPath))
            ->toContain(
                'namespace App\Settings;',
                'class SocialMediaSettings extends BaseSiteSettings',
                "protected string|null \$key = 'socialMedia';"
            );
});

it('also creates a data migration for the settings if the -m flag is set', function () {
    $expectedPath = app_path('Settings/SocialMediaSettings.php');
    cleanupFiles($expectedPath);
    expect(file_exists($expectedPath))->toBeFalse();

    cleanupSiteMigrations();
    expect(migrationExists('create_social_media_settings'))->toBeFalse();

    $this->artisan('site:settings', ['name' => 'social_media', '-m' => true])
        ->assertExitCode(0);

    expect($expectedPath)->toBeFile()
        ->and(file_get_contents($expectedPath))
            ->toContain(
                'namespace App\Settings;',
                'class SocialMediaSettings extends BaseSiteSettings',
                "protected string|null \$key = 'socialMedia';"
            )
        ->and(migrationExists('create_social_media_settings'))->toBeTrue();
});
