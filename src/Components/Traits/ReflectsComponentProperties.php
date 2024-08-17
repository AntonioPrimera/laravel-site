<?php
namespace AntonioPrimera\SiteComponents\View\Traits;

use ReflectionClass;
use ReflectionProperty;

trait ReflectsComponentProperties
{
    protected function getProperties(): array
    {
        $reflection = new ReflectionClass($this);
        return collect($reflection->getProperties(ReflectionProperty::IS_PUBLIC))
            ->filter(fn(ReflectionProperty $property) => $property->class === static::class)
            ->mapWithKeys(fn(ReflectionProperty $property) => [$property->getName() => $this->getPropertyData($property)])
            ->toArray();
    }

    protected function getPropertyData(ReflectionProperty $property): array
    {
        return [
            'name' => $property->getName(),
            'type' => $property->hasType() ? $property->getType() : null,
            'value' => $property->isInitialized($this) ? $property->getValue($this) : null,
            'default' => $property->hasDefaultValue() ? $property->getDefaultValue() : null,
        ];
    }
}
