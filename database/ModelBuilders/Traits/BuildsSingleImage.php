<?php

namespace AntonioPrimera\Site\Database\ModelBuilders\Traits;

trait BuildsSingleImage
{
    public function withImageFromMediaCatalog(string $imageRelativePath, string|array $alt): static
    {
        $this->model->setImageFromMediaCatalog($imageRelativePath, $alt);

        return $this;
    }

    public function deleteSectionImage(): static
    {
        if ($this->model->image) {
            $this->model->deleteMedia($this->model->image);
        }

        return $this;
    }
}
