<?php
namespace AntonioPrimera\Site\Models\Traits;

trait HasCustomTranslations
{

    /**
     * Get the translations as an array or as a string
     * - if the translations are already an array, they are returned as is
     * - if the translations are a string and $forceArray is true [currentLocale() => $translations] is returned
     * - if the translations are a string and $forceArray is false, $translations is returned
     */
    protected function getCustomTranslations(string|array $translations, bool $forceArray = false): array|string
    {
        //if the translations are already an array, return them as is (we don't check if the array is valid)
        if (is_array($translations))
            return $translations;

        return $forceArray ? [currentLocale() => $translations] : $translations;
    }

    /**
     * Get the translation for the requested locale
     * - if the translations are a string, the string is returned
     * - if the translations are an array, the requested locale is returned if it exists, otherwise the fallback locale is returned
     */
    protected function getCustomTranslation(string|array $translations, string|null $locale = null): string
    {
        $translations = $this->getCustomTranslations($translations);
        $requestedLocale = $locale ?? currentLocale();

        return is_string($translations)
            ? $translations
            : $translations[$requestedLocale] ?? $translations[fallbackLocale()] ?? config('site.translations.missing-translation');
    }

    /**
     * Set the translation for the requested locale
     * - if the translations are a string, [currentLocale() => $translations, $locale => $value] is returned
     * - if the translations are an array, the requested locale is set to the value
     */
    protected function setCustomTranslation(string|array $translations, string $locale, string $value): array
    {
        $translationsArray = $this->getCustomTranslations($translations, true);
        $translationsArray[$locale] = $value;

        return $translationsArray;
    }
}
