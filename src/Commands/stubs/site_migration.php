<?php
use AntonioPrimera\Site\Database\DataMigration;
use AntonioPrimera\Site\Database\ModelBuilders\SectionBuilder;

return new class extends DataMigration {

    public function up(): void
    {
        SectionBuilder::create(
            uid: 'home:hero',
            name: 'Home Hero',
            title: 'Welcome to our site',
            contents: 'Hero Contents',
            config: [
                'height' => ['h-[80vh]', 'md:h-[90vh]', 'lg:h-[80vh]'],
            ]
        )
            ->setImageFromMediaCatalog('home-hero.jpg', 'Hero Image');

        //$this->createSection('home:hero', 'Home Hero', 'Home', 'Home page contents');
        //
        //$this->createSection('about', 'About', 'info', 'About', 'About page contents');
        //$this->createSection('contact', 'Contact', 'mail', 'Contact', 'Contact page contents');
    }

    public function down(): void
    {

    }
};
