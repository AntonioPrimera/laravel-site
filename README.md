# A foundation to build configurable Websites using Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/antonioprimera/laravel-site.svg?style=flat-square)](https://packagist.org/packages/antonioprimera/laravel-site)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/antonioprimera/laravel-site/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/antonioprimera/laravel-site/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/antonioprimera/laravel-site/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/antonioprimera/laravel-site/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)

This package provides the basic building blocks to create a configurable website using Laravel and Filament as the
admin panel, where you can maintain the text and images of your website.

It provides a Section and a Bit model, where a Section contains some data of its own (title, contents) and can have
a collection of related Bits. A Bit is a data container, that can be used to store parts of site sections.

Both the Section and the Bit models have a single spatie media 'image' attached to them, which can be used to store
images related to the section or bit. If you want to store more images, you can create a new model and attach a media
collection to it.

This package doesn't provide any frontend views, but you can use the abstract `SiteComponent`, `SectionComponent` or
`BitComponent` classes to create your own view components. You can use the
[antonioprimera/laravel-site-components](https://github.com/AntonioPrimera/laravel-site-components) package to get
some pre-built components and artisan commands to create new View Components for Sections and Bits.

The Section and Bit models use `spatie/laravel-medialibrary` to store images and `spatie/laravel-translatable` to make
the title and contents translatable.

## Installation

You can install the package via composer:

```bash
composer require antonioprimera/laravel-site
```

This package provides built in model migrations, so you must run `artisan migrate` to run the migrations.

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-site-config"
```

This is the contents of the published config file:

```php
return [
    /**
     * The list of locales that should be used for translations
     * Make sure to include the default and fallback locale in this list
     */
    'translations' => [
        'locales' => ['en'],
        'missing-translation' => '--',  //the string that should be displayed if a translation is missing
    ],

    /**
     * Site section view component default configuration
     */
    'sections' => [

        //section images will be resized to fit these dimensions
        'image' => [
            'max-width' => 1920,
            'max-height' => 1080,
        ],
    ],

    /**
     * Data migrations configuration
     */
    'data-migrations' => [

        //the path to the directory where site data migrations are stored, relative to the database directory
        'path' => 'site-migrations',
    ],

    /**
     * Media catalog configuration
     */
    'media-catalog' => [
        //the disk where media catalog files are stored
        'disk' => 'media-catalog',
    ],

    'views' => [
        //the root path for the blade views of the site components (relative to the resources/views directory)
        'bladeRootName' => 'components.site',

        //the namespace of the site components
        'componentNamespace' => 'App\\View\\Components\\Site\\',
    ]
];
```

## Usage

The main feature of this package, is the ability to create data migrations, that can be used to seed the database with
site data. You can create a new data migration by running the following command:

```bash
php artisan make:site-migration MySectionOrBitDataMigration
```

These migration files are stored in the `database/site-migrations` directory and can are run using the `artisan migrate`
together with your standard laravel migrations.

You can use the `SectionBuilder` and `BitBuilder` classes to create new Section and Bit models in your data migrations,
which provide a fluent interface to create, update and delete the models (just don't forget to call the save()) method
to persist the changes to the database.

Here's an example of a data migration file, which creates a Section model with 2 Bit models attached to it:

```php
use AntonioPrimera\Site\Database\DataMigration;
use AntonioPrimera\Site\Database\ModelBuilders\SectionBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\BitBuilder;

return new class extends DataMigration {

    public function up(): void
    {
        SectionBuilder::create('home:hero', 'Home Hero Section')
            ->withTitle('Welcome to our website')
            ->withContents('This is the hero section of the home page')
            ->save()
            ->withImageFromMediaCatalog('path/to/home-hero-image.webp')
            ->createBit(
                uid: 'home:hero:cta',
                icon: 'heroicon-o-phone',
                title: 'Contact us',
                contents: '/contact',
                build: fn(BitBuilder $builder) => $builder->withImageFromMediaCatalog('path/to/cta-image.webp')
            )
            ->createBit(fn(BitBuilder $builder) => $builder
                ->withTitle('Our services')
                ->withIcon('heroicon-o-cog')
                ->withContents('We offer a wide range of services')
            )
    }

    public function down(): void
    {
        SectionBuilder::delete('home:hero');
    }
};
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
