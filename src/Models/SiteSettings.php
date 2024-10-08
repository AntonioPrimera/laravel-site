<?php
namespace AntonioPrimera\Site\Models;

use AntonioPrimera\Site\Exceptions\SiteException;

class SiteSettings
{
    protected Site|string $site = 'default';
    protected string|null $key = null;

    //--- Settings construction ---------------------------------------------------------------------------------------

    public function __construct(Site|string|null $site = null, string|null $key = null)
    {
        $this->site = site($site ?? $this->site);   //use the provided site or the default site
        $this->key = $key ?? $this->key;                 //use the provided key or the default key
        $this->fillPublicPropertiesWithSettings();
    }

    public static function instance(): static
    {
        return new static();
    }

    //--- Fetch settings ----------------------------------------------------------------------------------------------

    public function all()
    {
        return $this->site->getData($this->key);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $path = $this->key ? "$this->key.$key" : $key;
        return $this->site->getData($path, $default);
    }

    public static function setting(string $key, mixed $default = null): mixed
    {
        return static::instance()->get($key, $default);
    }

    //--- Manage settings ---------------------------------------------------------------------------------------------

    public function set(string $key, mixed $value): static
    {
        $path = $this->key ? "$this->key.$key" : $key;
        $this->site->setData($path, $value);
        return $this->save();
    }

    public function save(): static
    {
        $this->site->save();
        return $this;
    }

    public function clear(): static
    {
        $this->site->setData($this->key, []);
        return $this->save();
    }

    //--- Accessors ---------------------------------------------------------------------------------------------------

    public function siteInstance(): Site
    {
        return $this->site;
    }

    public function settingsKey(): string|null
    {
        return $this->key;
    }

    //--- Protected helpers -------------------------------------------------------------------------------------------

    protected function fillPublicPropertiesWithSettings(): void
    {
        $classReflection = new \ReflectionClass($this);
        $publicProperties = $classReflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        $settings = $this->all();
        if (!$settings)
            return;

        //make sure the settings are an array
        if (!is_array($settings))
            throw new SiteException('Settings must be an array for key: ' . $this->key);

        foreach ($publicProperties as $property)
            $this->fillProperty($property->getName(), $settings);
    }

    protected function fillProperty(string $name, array $settings): void
    {
        //only fill the property if it exists in the settings array
        if (!array_key_exists($name, $settings))
            return;

        $this->$name = $settings[$name];
    }
}
