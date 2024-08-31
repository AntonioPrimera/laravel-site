<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Components\Traits\IsConfigurable;
use AntonioPrimera\Site\Models\SiteComponent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

abstract class BaseSiteViewComponent extends Component
{
	use IsConfigurable;

    protected array $dynamicAttributes = [];    //model dynamic attributes (from the data container) exposed to the view
    protected array $modelAttributes = [];      //model attributes exposed to the view

    public function __construct(mixed $component, array $config = [])
    {
		$this->setInitialConfig($config);
        $this->setup($component);
		$this->mount();
    }

	protected function mount(): void
	{
		//override this method in child classes to prepare the data for the view
	}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        //get the root path for the blade views from the site config (e.g. 'components.sections')
        $bladeRoot = config('site.views.bladeRootName');
		$path = $this->relativeBladeName($this->relativeNamespace());
        return view("$bladeRoot.$path", array_merge($this->modelAttributes, $this->dynamicAttributes));
    }

    //--- Abstract methods --------------------------------------------------------------------------------------------

    /**
     * Determine the model instance for this component and set up the component properties
     */
    protected abstract function setup(mixed $componentOrUid): void;

    //--- Protected helpers -------------------------------------------------------------------------------------------

    /**
     * Determine the relative namespace of this component class in relation to the root namespace
     * e.g. App\View\Components\Site\Sections\Hero -> Sections\Hero (the root namespace is App\View\Components\Site)
     */
    protected function relativeNamespace(): string
    {
        $componentClass = get_class($this);
        $componentRootNamespace = config('site.views.componentNamespace');
        return trim(Str::after($componentClass, $componentRootNamespace), '\\');
    }

    /**
     * Determine the relative blade name of this component class in relation to the root namespace
     * e.g. for the relative namespace: Sections\LargeHero -> sections.large-hero
     */
    protected function relativeBladeName(string $relativeNamespace): string
    {
        return Str::of($relativeNamespace)->explode('\\')->map(fn ($part) => Str::kebab($part))->implode('.');
    }

    /**
     * Fill the $modelAttributes array with the model attributes that should be exposed to the view
     */
    protected function exposeModelAttributes(SiteComponent $model, array $attributeNames): void
    {
        foreach ($attributeNames as $attribute)
            $this->modelAttributes[$attribute] = $model->$attribute;
    }

    /**
     * Expose the unstructured data from the model to the view
     */
    protected function exposeModelUnstructuredData(SiteComponent $model): void
    {
        $this->dynamicAttributes = array_merge($this->dynamicAttributes, $model->getData() ?? []);
    }

    ///**
    // * Expose the model instance to the view by its class name (lowercase)
    // * e.g. if the model is a Section, expose it as $section to the view
    // */
    //protected function exposeModelByClassName($model): void
    //{
    //    $this->dynamicAttributes[Str::lower(class_basename($model))] = $model;
    //}
}
