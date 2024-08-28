<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

function contextPath(string $path): string
{
    return __DIR__ . '/Context/' . $path;
}

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

function migrateHomePage(): void
{
    //generate a migration file
    Artisan::call('site:migration', ['name' => 'DataMigrationCreateHomePage']);
    expect(migrationExists('data_migration_create_home_page'))->toBeTrue();

    //copy some relevant content into the migration file, so that it creates a section and a bit
    $migrationPath = getMigrationPath('data_migration_create_home_page');
    $stubPath = contextPath('stubs/data_migration_create_home_page.php');
    file_put_contents($migrationPath, file_get_contents($stubPath));

    //run the migrations and check if the section and bit were created
    Artisan::call('migrate');
}

function cleanupFile(string $path): void
{
    if (file_exists($path))
        unlink($path);
}

function cleanupFiles(...$paths): void
{
    foreach ($paths as $path)
        cleanupFile($path);
}
