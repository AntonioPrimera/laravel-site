<?php
namespace AntonioPrimera\Site\Database\ModelBuilders;

use AntonioPrimera\Site\Database\ModelBuilders\Traits\BuildsTranslatableTextContents;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Section;
use AntonioPrimera\Site\Models\Site;

/**
 * @property Page $model
 */
class PageBuilder extends SiteComponentBuilder
{
    use BuildsTranslatableTextContents;

    final public function __construct(Page $page)
    {
        parent::__construct($page);
    }

    //--- Factories ---------------------------------------------------------------------------------------------------

    public static function create(
        string $uid,
        string|null $name = null,
        string|array|null $title = null,
        string|array|null $short = null,
        string|array|null $contents = null,
        string|null $route = null,
        string|array|null $menuLabel = null,    //translatable
        bool $menuVisible = false,
        int $menuPosition = 0,
        array|null $data = null,
    ): static
    {
        //create the model with the minimum required data
        $page = site()->pages()->create(['uid' => $uid]);

        //add the rest of the data to the model, using the fluent interface
        return (new static($page))
            ->massAssignFluently(
                compact(
                    'name',
                    'title',
                    'short',
                    'contents',
                    'route',
                    'menuLabel',
                    'menuVisible',
                    'menuPosition',
                    'data'
                )
            )
            ->save();
    }

    /**
     * Create a new PageBuilder instance for an existing page
     */
    public static function from(Page|string $page): static
    {
        return new static(page($page));
    }

    //--- Static API --------------------------------------------------------------------------------------------------

    public static function deletePage(Page|string $page): void
    {
        static::from($page)->delete();
    }

    //--- Page Sections ------------------------------------------------------------------------------------------------

    public function createSection(
        string $uid,
        string|null $name = null,
        string|array|null $title = null,
        string|array|null $short = null,
        string|array|null $contents = null,
        int $position = 0,
        array|null $data = null,
        string|null $imageFromMediaCatalog = null,
        string $imageAlt = '',
        callable|null $build = null
    ): SectionBuilder
    {
        $builder = SectionBuilder::create(
            page: $this->model,
            uid: $uid,
            name: $name,
            title: $title,
            short: $short,
            contents: $contents,
            position: $position,
            data: $data,
            imageFromMediaCatalog: $imageFromMediaCatalog,
            imageAlt: $imageAlt
        );

        //if a $build callback is provided, call it with the new SectionBuilder instance
        if ($build)
            $this->updateSection($builder, $build);

        return $builder;
    }

    public function updateSection(SectionBuilder|Section|string $section, callable $build): static
    {
        $build($this->sectionBuilder($section));
        return $this;
    }

    public function deleteSection(Section|string $section): static
    {
        $this->sectionBuilder($section)->delete();
        return $this;
    }

    public function delete(): void
    {
        foreach ($this->model->sections as $section)
            $this->deleteSection($section);

        $this->model->delete();
    }

    //--- Fluent interface --------------------------------------------------------------------------------------------

    public function withRoute(string|null $route): static
    {
        $this->model->route = $route;
        return $this;
    }

    public function withMenuLabel(string|array|null $menuLabel, string|null $locale = null): static
    {
        return $this->setTranslatableProperty('menuLabel', $menuLabel, $locale);
    }

    public function withMenuVisible(bool $menuVisible): static
    {
        $this->model->menu_visible = $menuVisible;
        return $this;
    }

    public function withMenuPosition(int $menuPosition): static
    {
        $this->model->menu_position = $menuPosition;
        return $this;
    }

    //--- Protected helpers -------------------------------------------------------------------------------------------

    protected function sectionBuilder(SectionBuilder|Section|string $section): SectionBuilder
    {
        return $section instanceof SectionBuilder ? $section : SectionBuilder::from($section);
    }
}
