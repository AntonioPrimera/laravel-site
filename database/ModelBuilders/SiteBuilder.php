<?php
namespace AntonioPrimera\Site\Database\ModelBuilders;

use AntonioPrimera\Site\Models\Page;
use AntonioPrimera\Site\Models\Site;

/**
 * @property Site $model
 */
class SiteBuilder extends SiteComponentBuilder
{
    final public function __construct(Site $site)
    {
        parent::__construct($site);
    }

    //--- Factories ---------------------------------------------------------------------------------------------------

    public static function create(
        string $uid = 'default',
        string|null $name = null,
        array|null $data = null
    ): static
    {
        //create the model with the minimum required data
        $site = Site::create(['uid' => $uid]);

        //add the rest of the data to the model, using the fluent interface
        return (new static($site))
            ->massAssignFluently(compact('name', 'data'))
            ->save();
    }

    /**
     * Create a new SiteBuilder for an existing site
     */
    public static function from(Site|string $site): static
    {
        return new static(site($site));
    }

    //--- Static API --------------------------------------------------------------------------------------------------

    public static function deleteSite(Site|string $site): void
    {
        static::from($site)->delete();
    }

    //--- Site and page deletion --------------------------------------------------------------------------------------

    public function deletePage(Page|string $page): static
    {
        $this->pageBuilder($page)->delete();
        return $this;
    }

    public function deletePages(): static
    {
        $pages = $this->model->pages;

        foreach ($pages as $page)
            $this->deletePage($page);

        return $this;
    }

    public function delete(): void
    {
        //delete all pages
        $this->deletePages();

        //delete the site
        $this->model->delete();
    }

    //--- Protected helpers -------------------------------------------------------------------------------------------

    protected function pageBuilder(PageBuilder|Page|string $pageOrBuilder): PageBuilder
    {
        return $pageOrBuilder instanceof PageBuilder ? $pageOrBuilder : PageBuilder::from($pageOrBuilder);
    }
}
