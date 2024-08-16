<?php
namespace AntonioPrimera\Site\Database\ModelBuilders;

use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\SiteComponent;

abstract class SiteComponentBuilder
{
    public function __construct(protected SiteComponent $siteComponent)
    {
    }

    public function modelInstance(): SiteComponent
    {
        return $this->siteComponent;
    }

    public function save(): static
    {
        $this->siteComponent->save();
        return $this;
    }

    //--- Site Component config ---------------------------------------------------------------------------------------

    /**
     * Set a single configuration value or an array of configuration values
     *
     * If $key is a string, $value must be provided and will be set as the value for that key
     * If $key is an array, $value must be null and the array will be merged with the existing configuration
     */
    public function setConfig(array|string $key, mixed $value = null): static
    {
        $this->siteComponent->config($key, $value);
        return $this;
    }

    //--- Site Component deletion -------------------------------------------------------------------------------------

    public function delete(): void
    {
        $this->siteComponent->delete();
    }
}
