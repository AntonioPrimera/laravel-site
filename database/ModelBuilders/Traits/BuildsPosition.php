<?php
namespace AntonioPrimera\Site\Database\ModelBuilders\Traits;

trait BuildsPosition
{
    public function withPosition(int|string $position): static
    {
        $this->model->position = intval($position);
        return $this;
    }
}
