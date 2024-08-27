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
        Schema::create('sections', function (Blueprint $table) {
            //parent page
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();

            //add basic site component fields
            $this->addSiteComponentFields($table);			//id, uid, name, data, timestamps
			$this->addTranslatableContentFields($table);	//title, short, contents

            //sections can be sorted by their position
            $this->addPosition($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
