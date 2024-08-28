# A foundation to build configurable Websites using Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/antonioprimera/laravel-site.svg?style=flat-square)](https://packagist.org/packages/antonioprimera/laravel-site)

This package provides the basic building blocks to create a configurable website using Laravel and Filament as the
admin panel, where you can maintain the text and images of your website.

It provides the following models:
- Site: A Site is a collection of Pages and holds a settings container, that can be used to store global settings for the site.
- Page: A Page is a collection of Sections
- Section: A Section is a collection of Bits
- Bit: A Bit is a data container, that can be used to store parts of site sections.

All models have a `uid` attribute, used to identify and retrieve the models. The `uid` is a string that should be unique
within the model type. For example, in order to identify a model, you would use the Site Facade or the helpers:

```php
//gets the 'default' site
$site = site();

//gets the 'home' page of the 'default' site
$page = page('home');

//gets the 'hero' section of the 'home' page of the 'default' site
$section = section('home:hero');

//gets the 'cta' bit of the 'hero' section of the 'home' page of the 'default' site
$bit = bit('home:hero.cta');

//if you have several sites, you can specify the site key as the first argument for all the helpers
$bit = bit('my-site/home:hero.cta');
```

The Section and the Bit models have a single spatie media 'image', so you can easily associate images with them.

```php
//setting the image of a section
section('home:hero')->setImageFromMediaCatalog('path/to/image.webp');

//retrieving the image of a section
$mediaInstance = section('home:hero')->image;
```

This package provides abstract classes for building Page, Section and Bit View Components, that can be used to render
the models in your views. You can extend these classes to create your own View Components. You can use the following
bash commands to generate new View Components:

```bash
#generates a new Page View Component and a data migration if the -m flag is set
php artisan site:page AboutUs -m

#generates a new Section View Component
php artisan site:section Hero

#generates a new Bit View Component
php artisan site:bit Cta
```

Additionally, you can use the [antonioprimera/laravel-site-components](https://github.com/AntonioPrimera/laravel-site-components)
package to get some pre-built components and artisan commands to create new View Components for Sections and Bits.

The models use `spatie/laravel-medialibrary` to store images and `spatie/laravel-translatable` to make
the title and contents translatable.

## Installation

You can install the package via composer:

```bash
composer require antonioprimera/laravel-site
```

Publish the migrations and run them:

```bash
php artisan vendor:publish --tag="laravel-site-migrations"
php artisan migrate
```

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

    'model-builders' => [
        //whether the model builders should automatically save the model after each fluent method call
        //e.g. calling $builder->withName('test') will automatically save the model if this is set to true
        'fluent-auto-save' => true,
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
        'bladeRootName' => 'components',

        //the namespace of the site components
        'componentNamespace' => 'App\\View\\Components\\',
    ],

    //settings for the generator commands: site:page, site:section, site:bit
    'generator-command' => [
        'pages' => [
            'rootNamespace' => 'App\\View\\Components\\Pages',
            'classTargetFolder' => 'View/Components/Pages',         //relative to the project root
            'bladeTargetFolder' => 'components/pages',              //relative to the resources/views directory
        ],

        'sections' => [
            'rootNamespace' => 'App\\View\\Components\\Sections',
            'classTargetFolder' => 'View/Components/Sections',
            'bladeTargetFolder' => 'components/sections',
        ],

        'bits' => [
            'rootNamespace' => 'App\\View\\Components\\Bits',
            'classTargetFolder' => 'View/Components/Bits',
            'bladeTargetFolder' => 'components/bits',
        ],
    ]
];
```

## Usage

The package offers the following main features:
- Site, Page, Section and Bit models, to store and retrieve the data for your website
- View Component generators for Pages, Sections and Bits, so you can easily create new View Components and use the models in your views
- Data migrations, to seed the database with site data (Site, Pages, Sections and Bits using the SiteBuilder, PageBuilder, SectionBuilder and BitBuilder classes)
- Site Facade and helper functions to retrieve the models by their uid and to handle the site locales

### Create new data migrations

These migration files are stored in the `database/site-migrations` directory and can are run using the `artisan migrate`
together with your standard laravel migrations.

```bash
php artisan site:migration CreateHomePage
```

You can use the `SiteBuilder`, `PageBuilder`, `SectionBuilder` and `BitBuilder` classes to create new models in your data
migrations, which provide a fluent interface to create, update and delete the models.

Here's an example of a data migration file, which creates a Site, a Page and a Section model with 2 Bit models attached to it:

```php
use AntonioPrimera\Site\Database\DataMigration;
use AntonioPrimera\Site\Database\ModelBuilders\SiteBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\PageBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\SectionBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\BitBuilder;

return new class extends DataMigration {

    public function up(): void
    {
        //if this is the first migration, you should create the site first
        SiteBuilder::create('default', 'My Personal Branding Site')
            ->withData([
                'logo' => 'path/to/logo.webp',
                'favicon' => 'path/to/favicon.webp',
                'socialMedia' => [
                    'facebook' => 'https://facebook.com/my-profile',
                    'instagram' => 'https://instagram.com/my-profile',
                    'x' => 'https://x.com/my-profile',
                ],
            ]);
        
        //create the home page
        PageBuilder::create(
            uid: 'home',
            name: 'Home Page',
            title: 'My Home Page',
            short: 'This is my presentation home page',
            route: 'home',
            menuLabel: 'Home',
            menuVisible: true,
            menuPosition: 1,
            data: [
                'seo' => [
                    'title' => 'My Home Page',
                    'description' => 'This is the description of my home page',
                    'keywords' => 'home, page, website',
                ],
            ]
        );
        
        SectionBuilder::create(page: 'home', uid: 'hero', name: 'Home Hero Section')
            ->withTitle(['en' => 'Welcome to our website', 'de' => 'Willkommen auf unserer Webseite'])
            ->withContents(['en' => 'This is the hero section of the home page', 'de' => 'Dies ist der Hero-Bereich der Startseite'])
            ->withImageFromMediaCatalog('path/to/home-hero-image.webp')
            ->createBit(
                uid: 'cta',
                title: ['en' => 'Contact us', 'de' => 'Kontaktiere uns'],
                data: ['url' => '/contact', 'icon' => 'heroicon-o-phone'],
                build: fn(BitBuilder $builder) =>
                    $builder->withImageFromMediaCatalog('path/to/cta-background-image.webp', 'cta image alt text')
            )
            ->createBit(
                uid: 'motto',
                build: fn(BitBuilder $builder) =>
                    $builder->withTitle(['en' => 'Our Motto', 'de' => 'Unser Motto'])
                        ->withContents(['en' => 'This is our motto', 'de' => 'Das ist unser Motto'])
                        ->withImageFromMediaCatalog('path/to/motto-side-image.webp', 'our motto image alt text')
            );
    }

    public function down(): void
    {
        SectionBuilder::delete('home:hero');
    }
};
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
