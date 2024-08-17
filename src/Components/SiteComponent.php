<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Components\Traits\IsConfigurable;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

abstract class SiteComponent extends Component
{
	use IsConfigurable;

    public function __construct(array $config = [])
    {
		$this->setInitialConfig($config);
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
        return view("$bladeRoot.$path");
    }

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
}
