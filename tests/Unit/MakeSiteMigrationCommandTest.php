<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

function migrationExists(string $migrationName): bool
{
    $dir = database_path(config('site.data-migrations.path'));
    if (!is_dir($dir))
        return false;

    $snakeCaseName = Str::snake($migrationName);
    foreach (scandir($dir) as $file)
        if (str_ends_with($file, "$snakeCaseName.php"))
            return true;

    return false;
}

function getMigrationPath(string $migrationName): string|null
{
    $dir = database_path(config('site.data-migrations.path'));
    if (!is_dir($dir))
        return null;

    $snakeCaseName = Str::snake($migrationName);
    foreach (scandir($dir) as $file)
        if (str_ends_with($file, "$snakeCaseName.php"))
            return "$dir/$file";

    return null;
}

function cleanupSiteMigrations(): void
{
    $dir = database_path(config('site.data-migrations.path'));
    if (!is_dir($dir))
        return;

    $files = array_diff(scandir($dir), ['.', '..']);

    foreach ($files as $file)
        unlink("$dir/$file");

    rmdir($dir);
}

beforeEach(function () {
    cleanupSiteMigrations();
});

it('can create a new site migration', function () {
    expect(config('site.data-migrations.path'))->toBe('site-migrations')
        ->and(migrationExists('CreateHomePageHeroSection'))->toBeFalse();

    Artisan::call('make:site-migration', ['name' => 'CreateHomePageHeroSection']);

    expect(migrationExists('create_home_page_hero_section'))->toBeTrue();
});

it('runs migrations from the data-migrations folder when running artisan migrate', function () {
    //generate a migration file
    Artisan::call('make:site-migration', ['name' => 'CreateHomePageHeroSection']);
    expect(migrationExists('create_home_page_hero_section'))->toBeTrue();

    //copy some relevant content into the migration file, so that it creates a section and a bit
    $migrationPath = getMigrationPath('create_home_page_hero_section');
    $stubPath = contextPath('stubs/create_home_page_hero_section_migration.php.stub');
    file_put_contents($migrationPath, file_get_contents($stubPath));

    //run the migrations and check if the section and bit were created
    Artisan::call('migrate');

    $section = section('home:hero');
    $bit = bit('home:hero.cta');

    expect($section)->toBeInstanceOf(\AntonioPrimera\Site\Models\Section::class)
        ->and($bit)->toBeInstanceOf(\AntonioPrimera\Site\Models\Bit::class)
        ->and($section->name)->toBe('Home Hero')
        ->and($section->title)->toBe('Home Page')
        ->and($section->contents)->toBe('This is the best package ever!')
        ->and($bit->uid)->toBe('cta')
        ->and($bit->type)->toBe('cta')
        ->and($bit->name)->toBe('Contact us')
        ->and($bit->icon)->toBe('icon-contact-us')
        ->and($bit->title)->toBe('Contact us now!');
});
