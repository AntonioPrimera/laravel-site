<?php

use AntonioPrimera\Site\Models\SiteSettings;

beforeEach(function () {
    \AntonioPrimera\Site\Database\ModelBuilders\SiteBuilder::create('default', 'Test site', null);
});

it('can get an empty set of site settings if they are null or not set', function () {
    $siteSettings = new SiteSettings();
    expect($siteSettings->all())->toBeNull()
        ->and($siteSettings->get('randomKey'))->toBeNull()
        ->and($siteSettings->get('randomKey', 'default'))->toBe('default');
});

it('can set site settings to a null settings container', function () {
    $siteSettings = new SiteSettings();
    expect($siteSettings->siteInstance()->fresh()->data)->toBeNull();

    $siteSettings->set('someKey', 'someValue');

    expect($siteSettings->get('someKey'))->toBe('someValue')
        ->and($siteSettings->siteInstance()->fresh()->data)->toBe(['someKey' => 'someValue']);
});

it('can set site settings to a non-null settings container', function () {
    site()->update(['data' => ['someKey' => 'someValue']]);
    $siteSettings = new SiteSettings();

    expect($siteSettings->get('someKey'))->toBe('someValue');

    $siteSettings->set('anotherKey', 'anotherValue');

    expect($siteSettings->get('anotherKey'))->toBe('anotherValue')
        ->and($siteSettings->siteInstance()->fresh()->data)->toBe(['someKey' => 'someValue', 'anotherKey' => 'anotherValue']);
});

it('can create a new site settings instance with a specific key', function () {
    site()->update(['data' => ['someKey' => ['nestedKey' => 'nestedValue']]]);

    $siteSettings = new SiteSettings(key: 'someKey');
    expect($siteSettings->settingsKey())->toBe('someKey')
        ->and($siteSettings->get('nestedKey'))->toBe('nestedValue');
});

it ('will set the public properties of the site settings instance to the settings data', function () {
    site()->update(['data' => ['someKey' => 'someValue', 'anotherKey' => 'anotherValue', 'randomKey' => 'randomValue']]);
    $siteSettings = new class extends SiteSettings {
        public string|null $someKey;
        public string|null $anotherKey;
    };

    expect($siteSettings->someKey)->toBe('someValue')
        ->and($siteSettings->anotherKey)->toBe('anotherValue');
});

it('will get the public properties of the site settings to settings data in a nested key', function () {
    site()->update(['data' => ['level1' => ['level2' => ['level3' => ['nestedKey' => 'nestedValue']]]]]);
    $siteSettings = new class extends SiteSettings {
        public string|null $nestedKey;

        public function __construct()
        {
            parent::__construct(key: 'level1.level2.level3');
        }
    };

    expect($siteSettings->nestedKey)->toBe('nestedValue');
});
