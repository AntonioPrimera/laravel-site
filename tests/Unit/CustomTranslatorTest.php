<?php

use AntonioPrimera\Site\Tests\Context\CustomTranslator;

function setCurrentLocale(string $locale): void
{
    app()->setLocale($locale);
}

function setFallbackLocale(string $locale): void
{
    config(['app.fallback_locale' => $locale]);
}

function setAvailableLocales(array $locales): void
{
    config(['site.translations.available-locales' => $locales]);
}

it('will return the translations as is if forceArray is false', function () {
    $translator = new CustomTranslator();
    expect($translator->getTranslations('test', false))->toBe('test');
});

it('will return the translations as an array if forceArray is true', function () {
    $translator = new CustomTranslator();
    expect($translator->getTranslations('test', true))->toBe([currentLocale() => 'test']);
});

it('will return the translations as an array if they are already an array', function () {
    $translator = new CustomTranslator();
    expect($translator->getTranslations(['en' => 'test']))->toBe(['en' => 'test']);
});

it('will return the requested locale if it exists', function () {
    $translator = new CustomTranslator();
    expect($translator->getTranslation(['en' => 'test'], 'en'))->toBe('test');
});

it('will return the translation for the fallback locale if the requested locale does not exist', function () {
    $translator = new CustomTranslator();
    setFallbackLocale('en');
    expect($translator->getTranslation(['en' => 'test en'], 'de'))->toBe('test en');
});

it('will return the translation for given locale if the requested locale exists', function () {
    $translator = new CustomTranslator();
    expect($translator->getTranslation(['en' => 'test en', 'de' => 'test de'], 'de'))->toBe('test de');
});

it('will return the translation for the current locale if no locale is provided', function () {
    $translator = new CustomTranslator();
    setCurrentLocale('de');
    expect($translator->getTranslation(['en' => 'test en', 'de' => 'test de']))->toBe('test de');
});

it('will return the translation for the fallback locale if the current locale does not exist', function () {
    $translator = new CustomTranslator();
    setCurrentLocale('fr');
    setFallbackLocale('en');
    expect($translator->getTranslation(['en' => 'test en', 'de' => 'test de']))->toBe('test en');
});

it('will return the missing translation string if no translation is found', function () {
    $translator = new CustomTranslator();
    expect($translator->getTranslation(['hu' => 'test']))->toBe(config('site.translations.missing-translation'));
});

it('will return the translations string for any locale if the translations are a string', function () {
    $translator = new CustomTranslator();
    expect($translator->getTranslation('test'))->toBe('test')
        ->and($translator->getTranslation('test', 'de'))->toBe('test')
        ->and($translator->getTranslation('test', 'hu'))->toBe('test');
});

it('will set the translation for the requested locale', function () {
    $translator = new CustomTranslator();
    setCurrentLocale('en');
    expect(currentLocale())->toBe('en')
        ->and($translator->setTranslation('test', 'de', 'test de'))->toBe(['en' => 'test', 'de' => 'test de']);
});

it('will set the translation for the requested locale if the translations are already an array', function () {
    $translator = new CustomTranslator();
    expect($translator->setTranslation(['en' => 'test'], 'de', 'test de'))->toBe(['en' => 'test', 'de' => 'test de']);
});
