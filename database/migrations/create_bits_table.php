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
        Schema::create('bits', function (Blueprint $table) {
            //parent section
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();

            //add basic site component fields
            $this->addSiteComponentFields($table);                  //id, uid, name, data, timestamps
            $this->addTranslatableContentFields($table);            //title, short, contents
            $table->string('type')->nullable();             // optional type (e.g. 'stats')

            //bits can be sorted by their position
            $this->addPosition($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bits');
    }
};
