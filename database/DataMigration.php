<?php
namespace AntonioPrimera\Site\Database;

use AntonioPrimera\Site\Database\Traits\SiteStructureMigrationHelpers;
use Illuminate\Database\Migrations\Migration;

abstract class DataMigration extends Migration
{
    use SiteStructureMigrationHelpers;

    abstract public function up(): void;
    abstract public function down(): void;
}
