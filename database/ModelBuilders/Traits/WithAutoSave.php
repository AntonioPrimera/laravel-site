<?php
namespace AntonioPrimera\Site\Database\ModelBuilders\Traits;

trait WithAutoSave
{
    protected function autoSave(): static
    {
        if ($this->model->isDirty() && config('site.model-builders.fluent-auto-save', true))
            $this->model->save();

        return $this;
    }
}
