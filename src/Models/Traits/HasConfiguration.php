<?php
namespace AntonioPrimera\Site\Models\Traits;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Support\Collection;

/**
 * @property Collection $config
 */
trait HasConfiguration
{

    protected function initializeHasConfiguration(): void
    {
        //add the 'config' attribute to the casts array
        $this->mergeCasts([
            'config' => AsCollection::class,
        ]);
    }

    /**
     * Get / set the configuration for this model
     *
     * If the first parameter is a string, the method will return the value of the configuration key.
     * If the first parameter is an array, the method will merge the array with the existing config array.
     */
    public function config(string|array $key, mixed $default = null): mixed
    {
        //if the value is null, return the value from the config attribute
        if (is_string($key))
            return $this->getConfig($key, $default);

        //if the value is an array, merge the array with the config attribute
        $this->config->merge($key);
        $this->save();

        return $this;
    }

    public function getConfig(string $key, mixed $default = null): mixed
    {
        return $this->config->get($key, $default);
    }

    public function setConfig(string $key, mixed $value, bool $save = true): static
    {
        $this->config->put($key, $value);
        if ($save)
            $this->save();

        return $this;
    }
}
