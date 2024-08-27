<?php
namespace AntonioPrimera\Site\Models\Traits;

use Illuminate\Support\Arr;

/**
 * @property array|null $data
 */
trait HasUnstructuredData
{

    protected function initializeHasUnstructuredData(): void
    {
        //add the 'data' attribute to the casts array
        $this->mergeCasts([
            'data' => 'array',
        ]);
    }

    public function getData(string|null $key = null, mixed $default = null): mixed
    {
        if (!$key)
            return $this->data;

        return $this->data ? Arr::get($this->data, $key, $default) : $default;
    }

    public function setData(string|array $key, mixed $value): void
    {
        //if the key is an array, we'll just set the entire data container to that array
        if (is_array($key)) {
            $this->data = $key;
            return;
        }

        $data = $this->data ?? [];
        $this->data = Arr::set($data, $key, $value);
    }

    public function hasData(string $key): bool
    {
        return Arr::has($this->data, $key);
    }

    public function forgetData(string|array $keys): void
    {
        $data = $this->data ?? [];
        Arr::forget($data, $keys);
        $this->data = $data;
    }

    public function resetData(): void
    {
        $this->data = null;
    }
}
