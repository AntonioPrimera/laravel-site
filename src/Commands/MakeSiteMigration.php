<?php
namespace AntonioPrimera\Site\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeSiteMigration extends GeneratorCommand
{
    protected $name = 'site:make-migration {name}';
    protected $description = 'Create a new migration for the site package, creating, updating or deleting SiteComponent models (Sections, Bits, etc.)';

    protected $type = 'Migration';

    protected function getStub()
    {
        // TODO: Implement getStub() method.
    }
}
