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
        Schema::create('pages', function (Blueprint $table) {

            //parent site
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();

            //add basic site component fields
            $this->addSiteComponentFields($table);          //id, uid, name, data, timestamps
            $this->addTranslatableContentFields($table);    //title, short, contents

            //menu item properties
            $table->string('route')->nullable();
            $table->json('menu_label')->nullable();
            $table->boolean('menu_visible')->default(false);
            $table->integer('menu_position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
