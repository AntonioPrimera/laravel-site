<?php
namespace AntonioPrimera\Site\Components\Traits;

use Illuminate\Support\Arr;

trait IsConfigurable
{
	//override this in order to set default config values
	protected array $defaultConfig = [];

	//the config values for this component
	protected array $config = [];

	//--- Public API --------------------------------------------------------------------------------------------------

    //this is a shortcut for getting and setting config values, like the config() helper function in Laravel
	public function config(string|array $key, mixed $default = null): mixed
	{
        //if a string key is passed, return the value for that key or the default value
        //if an array is passed, merge it with the current config and return the merged array
        return is_string($key) ? $this->getConfig($key, $default) : $this->setConfig($key);
	}

    public function getConfig(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->config, $key, $default);
    }

    public function setConfig(array $config): array
    {
        return $this->config = array_merge($this->config, $config);
    }

	//--- Protected helpers -------------------------------------------------------------------------------------------

    //set the initial config, by merging the passed config with the default config
	protected function setInitialConfig(array $config): void
	{
		$this->config = array_merge($this->getDefaultConfig(), $config);
	}

    //override this in child classes to set default config values
    protected function getDefaultConfig(): array
    {
        return $this->defaultConfig;
    }
}
