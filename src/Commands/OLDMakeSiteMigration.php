<?php
namespace AntonioPrimera\Site\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class OLDMakeSiteMigration extends GeneratorCommand
{
    protected $name = 'make:site-migration';
    protected $description = 'Create a new migration for the site package, creating, updating or deleting SiteComponent models (Sections, Bits, etc.)';

    protected $type = 'Migration';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/site_migration.php.stub';
    }

    protected function qualifyClass($name): string
    {
        return $name;
    }

    protected function getPath($name): string
    {
        $snakeCaseName = Str::snake($name);
        return database_path(config('site.data-migrations.path') . '/' . date('Y_m_d_His') . '_' . $snakeCaseName . '.php');
    }
}
