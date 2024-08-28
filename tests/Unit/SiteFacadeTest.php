<?php

use AntonioPrimera\Site\Database\ModelBuilders\PageBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\SectionBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\SiteBuilder;
use AntonioPrimera\Site\Facades\Site;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Site as SiteModel;
use AntonioPrimera\Site\Models\SiteSettings;

it('can get a site by its uid', function () {
    SiteBuilder::create('some-site-uid', 'Test site');
    expect(Site::site('some-site-uid'))->toBeInstanceOf(SiteModel::class)
        ->and(Site::site('some-site-uid')->name)->toBe('Test site');
});

it('will use the default site if no site uid is provided', function () {
    SiteBuilder::create('default', 'Default site');
    expect(Site::site())->toBeInstanceOf(SiteModel::class)
        ->and(Site::site()->name)->toBe('Default site');
});

it('will throw an exception if the site uid is not found', function () {
    SiteBuilder::create('default', 'Default site');
    expect(fn() => Site::site('non-existing-site-uid'))->toThrow(\Exception::class);
});

it('can get a page by its uid', function () {
    SiteBuilder::create('default', 'Default site');
    $pageBuilder = PageBuilder::create('welcome', 'Welcome page');

    expect(Site::getPageByUid('welcome'))->toBeInstanceOf(\AntonioPrimera\Site\Models\Page::class)
        ->and(Site::getPageByUid('default/welcome'))->toBeInstanceOf(\AntonioPrimera\Site\Models\Page::class)
        ->and(Site::getPageByUid('default/welcome')->name)->toBe('Welcome page')
        ->and(Site::getPageByUid('welcome')->name)->toBe('Welcome page')
        ->and(Site::page('default/welcome')->name)->toBe('Welcome page')
        ->and(Site::page('welcome'))->toBeInstanceOf(\AntonioPrimera\Site\Models\Page::class)
        ->and(Site::page('welcome')->name)->toBe('Welcome page')
        ->and(Site::page($pageBuilder->model)->name)->toBe('Welcome page')
        ->and(Site::sitePage('welcome')->name)->toBe('Welcome page');
});

it('will throw an exception if the page uid is not found', function () {
    SiteBuilder::create('default', 'Default site');
    expect(fn() => Site::getPageByUid('non-existing-page-uid'))->toThrow(\Exception::class);
});

it('can get a section by its uid', function () {
    SiteBuilder::create('default', 'Default site');
    $pageBuilder = PageBuilder::create('welcome', 'Welcome page')
        ->createSection('hero-section', 'Hero section');

    expect(Site::getSectionByUid('default/welcome:hero-section'))->toBeInstanceOf(\AntonioPrimera\Site\Models\Section::class)
        ->and(Site::getSectionByUid('default/welcome:hero-section')->name)->toBe('Hero section')
        ->and(Site::getSectionByUid('welcome:hero-section')->name)->toBe('Hero section')
        ->and(Site::section('default/welcome:hero-section')->name)->toBe('Hero section')
        ->and(Site::section('welcome:hero-section')->name)->toBe('Hero section')
        ->and(Site::pageSection('hero-section', 'default/welcome')->name)->toBe('Hero section');
});

it('will throw an exception if the section uid is not found', function () {
    SiteBuilder::create('default', 'Default site');
    expect(fn() => Site::getSectionByUid('non-existing-section-uid'))->toThrow(\Exception::class);
});

it('can get a bit by its uid', function () {
    SiteBuilder::create('default', 'Default site');
    PageBuilder::create('welcome', 'Welcome page')
        ->createSection('hero-section', 'Hero section', build: function (SectionBuilder $builder) {
            $builder->createBit('cta', 'CTA bit');
        });

    expect(Site::getBitByUid('default/welcome:hero-section.cta'))->toBeInstanceOf(Bit::class)
        ->and(Site::getBitByUid('default/welcome:hero-section.cta')->name)->toBe('CTA bit')
        ->and(Site::getBitByUid('welcome:hero-section.cta')->name)->toBe('CTA bit')
        ->and(Site::bit('default/welcome:hero-section.cta')->name)->toBe('CTA bit')
        ->and(Site::bit('welcome:hero-section.cta')->name)->toBe('CTA bit')
        ->and(Site::sectionBit('cta', 'welcome:hero-section')->name)->toBe('CTA bit');
});

it('can get site settings directly from the site settings data container', function () {
    SiteBuilder::create(
        data: [
            'contact' => [
                'phone' => '123',
                'email' => 'test@test.com'
            ],
            'someSetting' => 'someValue'
        ]
    );

    expect(Site::settings('contact.phone'))->toBe('123')
        ->and(Site::settings('contact.email'))->toBe('test@test.com')
        ->and(Site::settings('someSetting'))->toBe('someValue')
        ->and(SiteSettings::instance()->get('contact.phone'))->toBe('123')
        ->and(SiteSettings::setting('contact.phone'))->toBe('123')
        ->and(siteSettings('contact.phone'))->toBe('123')
        ->and(settings('someSetting'))->toBe('someValue');
});
