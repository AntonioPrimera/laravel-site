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
    public SiteComponent $model;                    //the model instance for this component
    protected array $exposedModelAttributes = [];   //model attributes exposed to the view (will be made available in $modelAttributes)
    protected array $dynamicAttributes = [];        //model dynamic attributes (from the data container) exposed to the view
    protected array $modelAttributes = [];          //model attributes exposed to the view

    public function __construct(SiteComponent|string $component, array $config = [])
    {
		$this->setInitialConfig($config);
        $this->model = $this->determineModelInstance($component);
        $this->exposeModelByClassName($this->model);
        $this->exposeModelAttributes($this->model);
        $this->exposeModelUnstructuredData($this->model);

		$this->mount();
    }

	public function mount(): void
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
     * Return the model instance for this component
     * e.g. return section($componentOrUid) for a SectionViewComponent;
     */
    protected abstract function determineModelInstance(mixed $componentOrUid): SiteComponent;

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
    protected function exposeModelAttributes(SiteComponent $model): void
    {
        foreach ($this->exposedModelAttributes as $attribute)
            $this->modelAttributes[$attribute] = $model->$attribute;
    }

    /**
     * Expose the unstructured data from the model to the view
     */
    protected function exposeModelUnstructuredData(SiteComponent $model): void
    {
        $this->dynamicAttributes = array_merge($this->dynamicAttributes, $model->getData() ?? []);
    }

    /**
     * Expose the model instance to the view by its class name (lowercase)
     * e.g. if the model is a Section, expose it as $section to the view
     */
    protected function exposeModelByClassName($model): void
    {
        $this->dynamicAttributes[Str::lower(class_basename($model))] = $model;
    }
}
