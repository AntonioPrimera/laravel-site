<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Facades\Site;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\SiteComponent;
use Illuminate\Support\Collection;

/**
 * @property Page $model     //available in the component class
 *
 * View properties (only available in the view):
 * @property Page $page
 * @property string|null $title
 * @property string|null $short
 * @property string|null $contents
 *
 * @property Collection|null $sections
 */
abstract class PageViewComponent extends BaseSiteViewComponent
{
    protected array $exposedModelAttributes = ['title', 'short', 'contents'];

	//the sections of the page (additional data containers)
	public Collection|null $sections;

    public function __construct(string|Page|null $page = null, array $config = [])
    {
		parent::__construct($page, $config);
        $this->sections = $this->model->sections;
    }

    /**
     * Return the page instance for this component
     * This method is just syntactic sugar for 'determineModelInstance(...)'
     */
    protected abstract function page(string|Page|null $pageOrUid): Page;

    /**
     * Get a section of this page by its uid
     */
	public function section(string $uid): Section|null
	{
        return Site::pageSection($uid, $this->model);
	}

    //--- Implementation of abstract methods --------------------------------------------------------------------------

    protected function determineModelInstance(SiteComponent|string $componentOrUid): SiteComponent
    {
        return $this->page($componentOrUid);
    }
}
