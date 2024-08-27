<?php
namespace AntonioPrimera\Site\Database\ModelBuilders\Traits;

trait BuildsTranslatableTextContents
{
    //--- Fluent building interface -----------------------------------------------------------------------------------

    public function withTitle(string|array|null $title, string|null $locale = null): static
    {
        return $this->setTranslatableProperty('title', $title, $locale);
    }

    public function withShort(string|array|null $short, string|null $locale = null): static
    {
        return $this->setTranslatableProperty('short', $short, $locale);
    }

    public function withContents(string|array|null $contents, string|null $locale = null): static
    {
        return $this->setTranslatableProperty('contents', $contents, $locale);
    }

    //--- Protected helpers -------------------------------------------------------------------------------------------

    protected function setTranslatableProperty(string $property, string|array|null $translations, string|null $locale): static
    {
        //if no translations are provided, and there are no translations set on the site component, we'll just return
        if (is_null($translations) && empty($this->model->getTranslations($property)))
            return $this;

        if (is_array($translations)) {
            $this->model->setTranslations($property, $translations);
            return $this;
        }

        $this->model->setTranslation($property, locale($locale), $translations);
        return $this;
    }
}
