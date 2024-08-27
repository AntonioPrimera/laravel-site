<?php

namespace AntonioPrimera\Site\Database\Traits;

use Illuminate\Database\Schema\Blueprint;

trait MigratesSiteComponent
{

    protected function addSiteComponentFields(Blueprint $table): void
    {
        $table->id();

        $table->string('uid');
        $table->string('name')->nullable();

        $table->json('data')->nullable();

        $table->timestamps();
    }

    protected function addTranslatableContentFields(Blueprint $table): void
    {
        $table->json('title')->nullable();
        $table->json('short')->nullable();
        $table->json('contents')->nullable();
    }

    protected function addPosition(Blueprint $table): void
    {
        $table->integer('position')->default(0);
    }
}
