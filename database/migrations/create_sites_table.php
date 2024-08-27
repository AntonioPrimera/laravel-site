<?php

use AntonioPrimera\Site\Database\Traits\MigratesSiteComponent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use MigratesSiteComponent;

    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            //add basic site component fields
            $this->addSiteComponentFields($table);        //id, uid, name, data, timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
