<?php

namespace AntonioPrimera\Site\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Assign this trait to any model that needs to have a single image associated with it.
 *
 * Properties
 *
 * @property Media|null $image
 * @property string $imageAlt
 */
trait HasSingleImage
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->withResponsiveImages()
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('large')
                    ->format('webp')
                    ->fit(
                        Fit::Max,
                        config('site.sections.image.max-width'),
                        config('site.sections.image.max-height')
                    );
            });
    }

    //--- Accessors / Mutators ----------------------------------------------------------------------------------------

    /**
     * Get / set the image for this model
     */
    public function image(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getImage(),
            set: fn (Media $value) => $this->setImage($value->getPath())
        );
    }

    /**
     * Get / set the alternative text for the image
     */
    public function imageAlt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getImageAlt(),
            set: fn (string $value) => $this->setImageAlt($value)
        );
    }

    //--- Public API --------------------------------------------------------------------------------------------------

    /**
     * Get the image for this model
     */
    public function getImage(): ?Media
    {
        return $this->getFirstMedia('image');
    }

    /**
     * Set the image for this model
     */
    public function setImage(string $path, string $alt = ''): static
    {
        $this->addMedia($path)->withCustomProperties(['alt' => $alt])->toMediaCollection('image');

        return $this;
    }

    /**
     * Get the alternative text for the image
     */
    public function getImageAlt(): string
    {
        return $this->image?->getCustomProperty('alt', '') ?? '';
    }

    /**
     * Set the alternative text for the image
     */
    public function setImageAlt(string $alt): static
    {
        $this->image?->setCustomProperty('alt', $alt)->save();

        return $this;
    }

    /**
     * Set the image for this model from the media catalog
     */
    public function setImageFromMediaCatalog(string $imageName, string|array $alt): static
    {
        $this->addMedia($this->mediaCatalog($imageName))
            ->preservingOriginal()
            ->withCustomProperties(['alt' => $alt])
            ->toMediaCollection('image');

        return $this;
    }

    //--- Protected helpers -------------------------------------------------------------------------------------------

    /**
     * Get the full path to a file in the media catalog
     *
     * |!|: Make sure to create & set the correct disk in the config file
     */
    protected function mediaCatalog(string $fileName): string
    {
        $mediaCatalogDisk = config('site.media-catalog.disk');

        return Storage::disk($mediaCatalogDisk)->path($fileName);
    }

    protected function altText(string|array $alt): string
    {
        //todo: add support for translations
        $baseArray = is_array($alt) ? $alt : [];
    }
}
