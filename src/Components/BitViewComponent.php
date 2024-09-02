<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Models\Bit;

/**
 * View properties (only available in the view):
 * @property string|null $type
 * @property string|null $title
 * @property string|null $short
 * @property string|null $contents
 */
abstract class BitViewComponent extends BaseSiteViewComponent
{
    public Bit $bit;

    public function __construct(mixed $bit = null, array $config = [])
    {
		parent::__construct($bit, $config);
    }

    protected function getBitInstance(mixed $componentOrUid): Bit
    {
        return bit($componentOrUid);
    }

    //--- Implementation of abstract methods --------------------------------------------------------------------------

    final protected function setup(mixed $componentOrUid): void
    {
        //determine the section model instance
        $this->bit = $this->getBitInstance($componentOrUid);

        //expose the bit attributes to the view
        $this->exposeModelAttributes($this->bit, ['type', 'title', 'short', 'contents']);
        $this->exposeModelUnstructuredData($this->bit);
    }
}
