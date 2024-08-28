<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\SiteComponent;

/**
 * @property Bit $bit
 *
 * @property string|null $type
 * @property string|null $title
 * @property string|null $short
 * @property string|null $contents
 */
abstract class BitViewComponent extends BaseSiteViewComponent
{
    protected array $exposedModelAttributes = [ 'type', 'title', 'short', 'contents' ];

    public function __construct(mixed $bit = null, array $config = [])
    {
		parent::__construct($bit, $config);
    }

    //--- Implementation of abstract methods --------------------------------------------------------------------------

    protected function determineModelInstance(mixed $componentOrUid): SiteComponent
    {
        //if a bit() method exists in the child class, use it to get the model instance
        //otherwise, use the global bit() helper function
        return method_exists($this, 'bit')
            ? $this->bit($componentOrUid)
            : bit($componentOrUid);
    }
}
