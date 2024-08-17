<?php

/**
 * Testing the Section and Bit Builders (SectionBuilder, BitBuilder) Image handling:
 *  - attaching an image from the media catalog
 *  - assigning an alt text to the image with translations (array / string)
 *  - updating the alt text for the image for a specific locale
 *  - deleting the image
 */

//use AntonioPrimera\Site\Database\ModelBuilders\SectionBuilder;
//use AntonioPrimera\Site\Models\Section;
//use Illuminate\Support\Facades\Schema;
//use Illuminate\Support\Facades\Storage;
//use Spatie\MediaLibrary\MediaCollections\Models\Media;

//beforeEach(function () {
//    config(['filesystems.disks.test-media-catalog' => [
//        'driver' => 'local',
//        'root' => __DIR__ . '/../Context/storage/media-catalog',
//    ]]);
//
//    config(['filesystems.disks.test-media-library' => [
//        'driver' => 'local',
//        'root' => __DIR__ . '/../Context/storage/media-library',
//    ]]);
//
//    config(['site.media-catalog.disk' => 'test-media-catalog']);
//    config(['media-library.disk' => 'test-media-library']);
//});
//
//it ('created the correct test setup for handling media library images', function () {
//    //test that the media library disk is set correctly, so images can be retrieved from there
//    expect(Storage::disk('test-media-library')->exists('test-image.webp'))->toBeTrue()
//        ->and(Schema::hasTable('media'))->toBeTrue();
//});
//
//it('can create a section and attach a media library image to it', function () {
//    /* @var Section $section */
//    $section = Section::create(['uid' => 'test:section', 'name' => 'Test section']);
//
//    $section->addMedia(Storage::disk('test-media-catalog')->path('test-image.jpg'))
//        ->preservingOriginal()
//        ->toMediaCollection('image');
//
//    //SectionBuilder::create('test:section', 'Test section')
//    //    ->setImageFromMediaCatalog('test-image.webp', 'alt text');
//
//    $section = section('test:section');
//    expect($section->image)->toBeInstanceOf(Media::class)
//        ->and($section->image->file_name)->toBe('test-image.webp');
//});

//todo: fix if possible - finfo error when trying to determine the mime type of the image (memory overflow)

