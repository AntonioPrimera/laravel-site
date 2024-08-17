<?php

/**
 * Testing the Section and Bit Builders (SectionBuilder, BitBuilder):
 * - creating sections
 * - creating bits for new sections and existing sections
 * - updating section data
 * - updating bit data
 * - deleting sections
 * - deleting bits
 */

use AntonioPrimera\Site\Database\ModelBuilders\BitBuilder;
use AntonioPrimera\Site\Database\ModelBuilders\SectionBuilder;
use AntonioPrimera\Site\Models\Bit;
use AntonioPrimera\Site\Models\Section;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

beforeEach(function () {
});

it('can create a basic section with only an uid and a name', function () {
    expect(section('test:section'))->toBeNull();

    SectionBuilder::create('test:section', 'Test section');

    $section = section('test:section');
    expect($section)->toBeInstanceOf(Section::class)
        ->and($section->name)->toBe('Test section');
});

it('can use a fluent interface to set section data', function () {
    expect(section('test:section'))->toBeNull();
    SectionBuilder::create('test:section', 'Test section')
        ->setName('Updated section name')
        ->setTitle('Test section title')
        ->setContents('Test section contents')
        ->setConfig(['key' => 'value'])
        ->save();

    $section = section('test:section');
    expect($section->name)->toBe('Updated section name')
        ->and($section->title)->toBe('Test section title')
        ->and($section->contents)->toBe('Test section contents')
        ->and($section->config)->toBeInstanceOf(Collection::class)
        ->and($section->config->toArray())->toBe(['key' => 'value']);
});

it('can create a bit for a section', function () {
    SectionBuilder::create('test:section', 'Test section');
    expect(section('test:section')->bits()->count())->toBe(0);

    SectionBuilder::from('test:section')
        ->createBit('test-bit', 'test-type', 'Test bit', 'Test bit icon', 'Test bit title', 'Test bit contents', 5, ['key' => 'value']);

    $bit = bit('test:section.test-bit');
    expect($bit)->not->toBeNull()
        ->and($bit->name)->toBe('Test bit')
        ->and($bit->icon)->toBe('Test bit icon')
        ->and($bit->title)->toBe('Test bit title')
        ->and($bit->contents)->toBe('Test bit contents')
        ->and($bit->position)->toBe(5)
        ->and($bit->config)->toBeInstanceOf(Collection::class)
        ->and($bit->config->toArray())->toBe(['key' => 'value']);
});

it('can create a bit and fluently add data to it using the build callback', function () {
    SectionBuilder::create('test:section', 'Test section');
    expect(section('test:section')->bits()->count())->toBe(0);

    SectionBuilder::from('test:section')
        ->createBit(
            build: function (BitBuilder $builder) {
                $builder->setUid('test-bit')
                    ->setType('test-type')
                    ->setName('Test bit')
                    ->setIcon('Test bit icon')
                    ->setTitle('Test bit title')
                    ->setContents('Test bit contents')
                    ->setPosition(5)
                    ->setConfig(['key' => 'value'])
                    ->save();
            });

    $bit = bit('test:section.test-bit');
    expect($bit)->not->toBeNull()
        ->and($bit->name)->toBe('Test bit')
        ->and($bit->icon)->toBe('Test bit icon')
        ->and($bit->title)->toBe('Test bit title')
        ->and($bit->contents)->toBe('Test bit contents')
        ->and($bit->position)->toBe(5)
        ->and($bit->config)->toBeInstanceOf(Collection::class)
        ->and($bit->config->toArray())->toBe(['key' => 'value']);
});

it('can update an existing section using the SectionBuilder', function () {
    SectionBuilder::create('test:section', 'Test section');
    expect(section('test:section')->name)->toBe('Test section');

    SectionBuilder::from('test:section')
        ->setName('Updated section name')
        ->setTitle('Test section title')
        ->setContents('Test section contents')
        ->setConfig(['key' => 'value'])
        ->save();

    $section = section('test:section');
    expect($section->name)->toBe('Updated section name')
        ->and($section->title)->toBe('Test section title')
        ->and($section->contents)->toBe('Test section contents')
        ->and($section->config)->toBeInstanceOf(Collection::class)
        ->and($section->config->toArray())->toBe(['key' => 'value']);
});

it('can update a bit using the SectionBuilder', function () {
    SectionBuilder::create('test:section', 'Test section')
        ->createBit('test-bit', 'test-type', 'Test bit');

    $bit = bit('test:section.test-bit');
    expect($bit->name)->toBe('Test bit')
        ->and($bit->type)->toBe('test-type');

    SectionBuilder::from('test:section')
        ->updateBit('test-bit', function (BitBuilder $builder) {
            $builder->setName('Updated bit name')
                ->setType('Updated bit type')
                ->save();
        });

    $bit = bit('test:section.test-bit');
    expect($bit->name)->toBe('Updated bit name')
        ->and($bit->type)->toBe('Updated bit type');
});

it('can update a section bit directly using the BitBuilder', function () {
    SectionBuilder::create('test:section', 'Test section')
        ->createBit('test-bit', 'test-type', 'Test bit', 'Test bit icon', 'Test bit title', 'Test bit contents', 5, ['key' => 'value']);

    $bit = bit('test:section.test-bit');
    expect($bit->name)->toBe('Test bit');

    BitBuilder::from($bit)
        ->setName('Updated bit name')
        ->setIcon('Updated bit icon')
        ->setTitle('Updated bit title')
        ->setContents('Updated bit contents')
        ->setPosition(10)
        ->setConfig(['key' => 'updated value'])
        ->save();

    $bit = bit('test:section.test-bit');
    expect($bit->name)->toBe('Updated bit name')
        ->and($bit->icon)->toBe('Updated bit icon')
        ->and($bit->title)->toBe('Updated bit title')
        ->and($bit->contents)->toBe('Updated bit contents')
        ->and($bit->position)->toBe(10)
        ->and($bit->config)->toBeInstanceOf(Collection::class)
        ->and($bit->config->toArray())->toBe(['key' => 'updated value']);
});

it('can create multiple bits for a section using the fluent interface', function () {
    SectionBuilder::create('test:section', 'Test section')
        ->createBit('test-bit-1')
        ->createBit('test-bit-2')
        ->createBit('test-bit-3');

    expect(section('test:section')->bits()->count())->toBe(3)
        ->and(bit('test:section.test-bit-1'))->not->toBeNull()
        ->and(bit('test:section.test-bit-2'))->not->toBeNull()
        ->and(bit('test:section.test-bit-3'))->not->toBeNull();
});

it('can delete all bits of a section', function () {
    SectionBuilder::create('test:section', 'Test section')
        ->createBit('test-bit-1')
        ->createBit('test-bit-2')
        ->createBit('test-bit-3');

    expect(section('test:section')->bits()->count())->toBe(3);

    SectionBuilder::from('test:section')->deleteBits();

    expect(section('test:section')->bits()->count())->toBe(0);
});

it('can delete all bits of a certain type of a section', function () {
    SectionBuilder::create('test:section', 'Test section')
        ->createBit('test-bit-1', 'test-type-1')
        ->createBit('test-bit-2', 'test-type-2')
        ->createBit('test-bit-3', 'test-type-1');

    expect(section('test:section')->bits()->count())->toBe(3);

    SectionBuilder::from('test:section')->deleteBits('test-type-1');

    expect(section('test:section')->bits()->count())->toBe(1)
        ->and(section('test:section')->bits->first()->uid)->toBe('test-bit-2');
});

it('can delete a section and its bits', function () {
    $sectionCount = Section::count();
    $bitCount = Bit::count();

    SectionBuilder::create('test:section', 'Test section')
        ->createBit('test-bit-1')
        ->createBit('test-bit-2')
        ->createBit('test-bit-3');

    expect(Section::count())->toBe($sectionCount + 1)
        ->and(Bit::count())->toBe($bitCount + 3);

    SectionBuilder::from('test:section')->delete();

    expect(Section::count())->toBe($sectionCount)
        ->and(Bit::count())->toBe($bitCount);
});
