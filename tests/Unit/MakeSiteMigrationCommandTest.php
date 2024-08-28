<?php

use AntonioPrimera\Site\Facades\Site;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    cleanupSiteMigrations();
});

it('can create a new site migration', function () {
    expect(config('site.data-migrations.path'))->toBe('site-migrations')
        ->and(migrationExists('DataMigrationCreateHomePage'))->toBeFalse();

    Artisan::call('site:migration', ['name' => 'DataMigrationCreateHomePage']);

    expect(migrationExists('data_migration_create_home_page'))->toBeTrue();
});

it('runs migrations from the data-migrations folder when running artisan migrate', function () {
    config(['app.locale' => 'ro']);
    config(['app.fallback_locale' => 'en']);
    config(['site.translations.locales' => ['en', 'ro', 'de']]);

    migrateHomePage();

    $site = site();
    $homePage = page('home');
    $headerSection = section('home:header');
    $heroSection = section('home:hero');
    $heroStats = section('home:stats');
    $ctaBit = bit('home:hero.cta');
    $stat1Bit = bit('home:stats.stat-1');
    $stat2Bit = bit('home:stats.stat-2');

    Site::setLocale('ro');

    //test the site
    expect($site)->toBeInstanceOf(\AntonioPrimera\Site\Models\Site::class)
        ->and($site->name)->toBe('Test site')
        ->and($site->name)->toBe('Test site')

        //test the home page
        ->and($homePage->name)->toBe('Home Page')
        ->and($homePage->title)->toBe('Home Page Title')
        ->and($homePage->short)->toBe('Home Page Short')
        ->and($homePage->contents)->toBe('Home Page Contents')
        ->and($homePage->getData('seo_title'))->toBe('Home SEO Title')
        ->and($homePage->getData('seo_description'))->toBe('Home SEO Description')

        //test the home:header section
        ->and($headerSection->position)->toBe(1)

        //test the home:hero section
        ->and($heroSection->position)->toBe(2)
        ->and($heroSection->name)->toBe('Home Hero')
        ->and($heroSection->short)->toBe('Home Hero Short')
        ->and($heroSection->getData('height'))->toBe('80vh')

        //test the home:hero.cta bit
        ->and($ctaBit->name)->toBe('cta')
        ->and($ctaBit->title)->toBe('Contact us')
        ->and($ctaBit->short)->toBe('Contact us now!')
        ->and($ctaBit->getData('icon'))->toBe('heroicon-o-phone')

        //test the home:stats section
        ->and($heroStats->position)->toBe(3)
        ->and($heroStats->name)->toBe('Home Hero Stats')
        ->and($heroStats->short)->toBe('Home Hero Stats Short Ro')  //the ro translation, because locale is ro
        ->and($heroStats->contents)->toBe('Home Hero Stats Contents Ro')    //the ro translation
        ->and($heroStats->title)->toBe('Home Hero Stats Title Ro')          //the ro translation
        ->and($heroStats->getData('overlay'))->toBe('dark')

        //test the home:stats.stat-1 bit
        ->and($stat1Bit->type)->toBe('stat')
        ->and($stat1Bit->name)->toBe('Stat 1')
        ->and($stat1Bit->title)->toBe('Our Services Ro')    //the ro translation
        ->and($stat1Bit->short)->toBe('Our Services Short Ro')    //the ro translation
        ->and($stat1Bit->contents)->toBe('Our Services Contents Ro')    //the ro translation
        ->and($stat1Bit->position)->toBe(1)

        //test the home:stats.stat-2 bit
        ->and($stat2Bit->type)->toBe('stat')
        ->and($stat2Bit->name)->toBe('Stat 2')
        ->and($stat2Bit->title)->toBe('Make an Appointment Ro')    //the ro translation
        ->and($stat2Bit->position)->toBe(2)
        ->and($stat2Bit->getData('icon'))->toBe('heroicon-o-calendar')
        ->and($stat2Bit->getData('route'))->toBe('appointments.create');

    Site::setLocale('en');

    //test the home page translations for title, short, contents and menu_label
    expect($homePage->title)->toBe('')
        ->and($homePage->short)->toBe('')
        ->and($homePage->contents)->toBe('')
        ->and($homePage->menu_label)->toBe('')
        ->and($homePage->getData('seo_title'))->toBe('Home SEO Title')
        ->and($homePage->getData('seo_description'))->toBe('Home SEO Description')

        //test the home:hero section translations for title, short and contents
        ->and($heroSection->title)->toBe('')
        ->and($heroSection->short)->toBe('')
        ->and($heroSection->contents)->toBe('')

        //test the home:stats section translations for title, short and contents
        ->and($heroStats->title)->toBe('Home Hero Stats Title En')
        ->and($heroStats->short)->toBe('Home Hero Stats Short En')
        ->and($heroStats->contents)->toBe('Home Hero Stats Contents En')

        //test the home:stats.stat-1 bit translations for title, short and contents
        ->and($stat1Bit->title)->toBe('Our Services En')
        ->and($stat1Bit->short)->toBe('Our Services Short En')
        ->and($stat1Bit->contents)->toBe('Our Services Contents En')

        //test the about page translations for menu_label
        ->and(page('about')->menu_label)->toBe('About Us En')

        //check that by default the sections and bits are fetched ordered by position
        ->and($homePage->sections->pluck('uid')->toArray())->toBe(['header', 'hero', 'stats'])
        ->and($heroStats->bits->pluck('uid')->toArray())->toBe(['stat-1', 'stat-2']);

    //change the fallback locale to ro
    config(['app.fallback_locale' => 'ro']);
    Site::setLocale('en');

    //now the home page translations should be in ro (because en was not provided during build)
    expect($homePage->title)->toBe('Home Page Title')
        ->and($homePage->short)->toBe('Home Page Short')
        ->and($homePage->contents)->toBe('Home Page Contents')
        ->and($homePage->menu_label)->toBe('Home')
        ->and($homePage->getData('seo_title'))->toBe('Home SEO Title')
        ->and($homePage->getData('seo_description'))->toBe('Home SEO Description')

        //test the home:hero section translations for title, short and contents
        ->and($heroSection->title)->toBe('Home Hero Title')
        ->and($heroSection->short)->toBe('Home Hero Short')
        ->and($heroSection->contents)->toBe('Home Hero Contents');

    //test the generic section and generic bit
    $footerSection = section('footer');
    $ctaBit = bit('cta');

    Site::setLocale('ro');
    expect($footerSection->name)->toBe('Site Footer')
        ->and($footerSection->title)->toBe('Site Footer Ro')

        ->and($ctaBit->type)->toBe('generic-cta')
        ->and($ctaBit->name)->toBe('Call to Action')
        ->and($ctaBit->title)->toBe('Contact us Ro');
});
