<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bits', function (Blueprint $table) {
            $table->id();

            //all bits belong to a section
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();

            //bit identity
            $table->string('uid')->nullable();              // optional unique identifier (unique to the section)
            $table->string('type')->nullable();             // optional type (e.g. 'stats')
            $table->string('name')->nullable();             // optional name, used in the admin panel

            //bit contents
            $table->string('icon')->nullable();             // optional icon
            $table->string('title')->nullable();            // optional title
            $table->text('contents')->nullable();           // optional contents

            //bit configuration
            $table->integer('position')->default(0);  // optional position, for sorting
            $table->json('config')->nullable();             // optional config (e.g. ['spacing' => 'lg', 'dark' => true, ...])

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bits');
    }
};
