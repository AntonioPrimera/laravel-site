<?php
namespace AntonioPrimera\Site\Tests\Context;

use AntonioPrimera\Site\Models\Traits\HasCustomTranslations;

/**
 * This is a dummy class used to test the HasCustomTranslations trait
 *
 * @method array|string getCustomTranslations(string|array $translations, bool $forceArray = false)
 * @method string getCustomTranslation(string|array $translations, string|null $locale = null)
 * @method array setCustomTranslation(string|array $translations, string $locale, string $value)
 */
class CustomTranslator
{
    use HasCustomTranslations;

    public function __call(string $name, array $arguments)
    {
        return $this->$name(...$arguments);
    }
}
