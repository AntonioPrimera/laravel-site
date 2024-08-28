<?php
namespace AntonioPrimera\Site\Database\ModelBuilders\Traits;

trait BuildsPosition
{
    use WithAutoSave;

    public function withPosition(int|string $position): static
    {
        $this->model->position = intval($position);
        $this->autoSave();
        return $this;
    }
}
