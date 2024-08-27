<?php

namespace AntonioPrimera\Site\Database\ModelBuilders;

use AntonioPrimera\Site\Models\SiteComponent;
use Illuminate\Support\Str;

abstract class SiteComponentBuilder
{
    public function __construct(public SiteComponent $model) {}

    public function save(): static
    {
        $this->model->save();

        return $this;
    }

    //--- Fluent building interface -----------------------------------------------------------------------------------

    public function withUid(string $uid): static
    {
        $this->model->uid = $uid;

        return $this;
    }

    public function withName(string $name): static
    {
        $this->model->name = $name;

        return $this;
    }

    /**
     * Set a dynamic property on the site component
     */
    public function withData(array|string|null $key, mixed $value = null): static
    {
        if (is_null($key)) {
            $this->model->resetData();
            return $this;
        }

        $this->model->setData($key, $value);
        return $this;
    }

    //--- Site Component deletion -------------------------------------------------------------------------------------

    public function delete(): void
    {
        $this->model->delete();
    }

    //--- Protected helpers -------------------------------------------------------------------------------------------

    protected function massAssignFluently(array $properties): static
    {
        foreach ($properties as $key => $value) {
            if (method_exists($this, $method = 'with' . Str::ucfirst($key)) && $value)
                $this->$method($value);
        }

        return $this;
    }
}
