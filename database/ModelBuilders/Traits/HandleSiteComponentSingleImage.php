<?php

namespace AntonioPrimera\Site\Database\ModelBuilders\Traits;

trait HandleSiteComponentSingleImage
{

    public function setImageFromMediaCatalog(string $imageRelativePath, string|array $alt): static
    {
        $this->siteComponent->setImageFromMediaCatalog($imageRelativePath, $alt);
        return $this;
    }

    public function deleteSectionImage(): static
    {
        if ($this->siteComponent->image)
            $this->siteComponent->deleteMedia($this->siteComponent->image);

        return $this;
    }
}
