<?php

namespace AntonioPrimera\Site\Database;

use Illuminate\Database\Migrations\Migration;

abstract class DataMigration extends Migration
{
    abstract public function up(): void;

    abstract public function down(): void;
}
