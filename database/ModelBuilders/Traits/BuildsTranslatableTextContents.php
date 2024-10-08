<?php
namespace AntonioPrimera\Site\Database\ModelBuilders\Traits;

use Illuminate\Support\Str;

trait BuildsTranslatableTextContents
{
    use WithAutoSave;

    //--- Fluent building interface -----------------------------------------------------------------------------------

    public function withTitle(string|array|null $title, string|null $locale = null): static
    {
        return $this->setTranslatableProperty('title', $title, $locale)->autoSave();
    }

    public function withShort(string|array|null $short, string|null $locale = null): static
    {
        return $this->setTranslatableProperty('short', $short, $locale)->autoSave();
    }

    public function withContents(string|array|null $contents, string|null $locale = null): static
    {
        return $this->setTranslatableProperty('contents', $contents, $locale)->autoSave();
    }

    //--- Protected helpers -------------------------------------------------------------------------------------------

    protected function setTranslatableProperty(string $property, string|array|null $translations, string|null $locale): static
    {
        $translatableProperty = Str::snake($property);

        //if no translations are provided, and there are no translations set on the site component, we'll just return
        if (is_null($translations) && empty($this->model->getTranslations($translatableProperty)))
            return $this;

        if (is_array($translations)) {
            $this->model->setTranslations($translatableProperty, $translations);
            return $this;
        }

        $this->model->setTranslation($translatableProperty, locale($locale), $translations);
        return $this;
    }
}
