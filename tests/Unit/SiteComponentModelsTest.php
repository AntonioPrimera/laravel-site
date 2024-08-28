<?php
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    cleanupSiteMigrations();
    migrateHomePage();
});

it('can correctly determine the fully qualified uid for each site component model', function(){
    expect(site()->fullyQualifiedUid())->toBe('default')
        ->and(page('home')->fullyQualifiedUid())->toBe('default/home')
        ->and(section('home:header')->fullyQualifiedUid())->toBe('default/home:header')
        ->and(bit('home:hero.cta')->fullyQualifiedUid())->toBe('default/home:hero.cta');
});
