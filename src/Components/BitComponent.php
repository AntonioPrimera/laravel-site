<?php
namespace AntonioPrimera\Site\Components;

use AntonioPrimera\Site\Models\Bit;

abstract class BitComponent extends SiteComponent
{
	public Bit $bit;

	//the type, icon, title and contents, taken from the bit model
    public string|null $type;
    public string|null $icon;
	public string|null $title;
	public string|null $contents;

    public function __construct(string|Bit $bit = null, array $config = [])
    {
		parent::__construct($config);

        $this->bit = $this->determineBitInstance($bit);
		$this->fillPropertiesWithBitData($this->bit);
    }

	//--- Protected helpers -------------------------------------------------------------------------------------------

	protected function determineBitInstance(string|Bit $bit): Bit
	{
		return $bit instanceof Bit ? $bit : Bit::where('uid', $bit)->firstOrFail();
	}

	protected function fillPropertiesWithBitData(Bit $bit): void
	{
        $this->type = $bit->type;
        $this->icon = $bit->icon;
		$this->title = $bit->title;
		$this->contents = $bit->contents;
	}
}
