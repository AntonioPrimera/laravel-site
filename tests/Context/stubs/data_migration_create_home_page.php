<?php
use AntonioPrimera\Site\Database\DataMigration;
use AntonioPrimera\Site\Database\ModelBuilders\BitBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\PageBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\SectionBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\SiteBuilder;

return new class extends DataMigration {

    public function up(): void
    {
        SiteBuilder::create('default', 'Test site');
        PageBuilder::create('home', 'Home Page', 'Home Page Title', 'Home Page Short', 'Home Page Contents', 'home', 'Home', true, 1, ['seo_title' => 'Home SEO Title', 'seo_description' => 'Home SEO Description']);
        SectionBuilder::create('home', 'hero', 'Home Hero', 'Home Hero Title', 'Home Hero Short', 'Home Hero Contents', 2, ['height' => '80vh'])
            ->createBit('cta', 'cta', 'Contact us', 'Contact us', 'Contact us now!', 'Arrange a meeting with us', 5, ['icon' => 'heroicon-o-phone']);

        SectionBuilder::create('home', 'stats')
            ->withName('Home Hero Stats')
            ->withShort(['ro' => 'Home Hero Stats Short Ro', 'en' => 'Home Hero Stats Short En'])
            ->withContents(['ro' => 'Home Hero Stats Contents Ro', 'en' => 'Home Hero Stats Contents En'])
            ->withTitle(['ro' => 'Home Hero Stats Title Ro', 'en' => 'Home Hero Stats Title En'])
            ->withPosition(3)
            ->withData(['overlay' => 'dark'])
            ->createBit('stat-2', build: fn(BitBuilder $builder) => $builder
                ->withType('stat')
                ->withName('Stat 2')
                ->withTitle(['ro' => 'Make an Appointment Ro', 'en' => 'Make an Appointment En'])
                ->withShort(['ro' => 'Make an Appointment Short Ro', 'en' => 'Make an Appointment Short En'])
                ->withContents(['ro' => 'Make an Appointment Contents Ro', 'en' => 'Make an Appointment Contents En'])
                ->withPosition(2)
                ->withData('icon', 'heroicon-o-calendar')
                ->withData('route', 'appointments.create')
            )
            ->createBit('stat-1', build: fn(BitBuilder $builder) => $builder
                ->withType('stat')
                ->withName('Stat 1')
                ->withTitle(['ro' => 'Our Services Ro', 'en' => 'Our Services En'])
                ->withShort(['ro' => 'Our Services Short Ro', 'en' => 'Our Services Short En'])
                ->withContents(['ro' => 'Our Services Contents Ro', 'en' => 'Our Services Contents En'])
                ->withPosition(1)
            );

        SectionBuilder::create('home', 'header', position: 1);

        PageBuilder::create('about', menuLabel: ['ro' => 'About Us Ro', 'en' => 'About Us En'], menuVisible: true, menuPosition: 2);

        //create a generic section
        SectionBuilder::createGenericSection('footer', 'Site Footer', title: ['ro' => 'Site Footer Ro', 'en' => 'Site Footer En']);

        //create a generic bit
        BitBuilder::createGenericBit(uid: 'cta', name: 'Call to Action', type:'generic-cta', title: ['ro' => 'Contact us Ro', 'en' => 'Contact us']);
    }

    public function down(): void
    {
        SiteBuilder::deleteSite('default');
    }
};
