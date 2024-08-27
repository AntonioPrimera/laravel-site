<?php
namespace AntonioPrimera\Site\Models\Traits;

/**
 * Properties
 * @property string|null $title
 * @property string|null $short
 * @property string|null $contents
 */
trait HasTextContents
{
    //common translatable attributes for all site components
    protected array $translatableTextContents = ['title', 'short', 'contents'];
}
