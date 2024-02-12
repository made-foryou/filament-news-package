<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use MadeForYou\Categories\Models\Category;
use MadeForYou\News\Exceptions\NoMainCategory;
use MadeForYou\News\Models\Post;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

it('can be inserted into the database', function () {
    $post = new Post([
        'title' => 'test',
    ]);

    $post->save();

    assertDatabaseHas('made_posts', [
        'title' => 'test',
    ]);
});

it('can have a main category', function () {
    $category = new Category([
        'name' => 'Test category',
    ]);

    $category->save();

    $post = new Post([
        'title' => 'test',
    ]);

    $post->category()->associate($category);

    $post->save();

    assertDatabaseHas('made_posts', [
        'title' => 'test',
        'category_id' => $category->id,
    ]);

    expect($post->category)
        ->toBeInstanceOf(Category::class)
        ->and($post->category->id)
        ->toBe($category->id);
});

it('throws an error when the main_category is not being used', function () {
    config()->set('filament-news.use_main_category', false);

    $post = new Post([
        'title' => 'test',
    ]);

    $post->category();
})->throws(NoMainCategory::class);

it('can generate its url', function () {
    $post = new Post([
        'title' => fake()->sentence(),
    ]);

    $post->save();

    expect($post->getUrl())->toBe(Str::slug($post->title));

    $post = new Post([
        'title' => fake()->sentence(),
    ]);

    $category = new Category([
        'name' => fake()->sentence(),
    ]);

    $post->category()->associate($category);

    expect($post->getUrl())
        ->toBe(
            Str::slug($category->name) . '/' . Str::slug($post->title)
        );
});

it('has a route name', function () {
    $post = Post::factory()->createOne();

    expect($post->getRouteName())->toBe('news.' . $post->id);
});

it('registers the media collections', function () {
    $post = Post::factory()
        ->createOne();

    $post->registerMediaCollections();

    expect((count($post->mediaCollections) === 0))
        ->toBeFalse()
        ->and($post->getMediaCollection('poster'))
        ->toBeInstanceOf(MediaCollection::class)
        ->and($post->getMediaCollection('poster')->name)
        ->toBe('poster');
});

it('can register media conversions', function () {
    $post = Post::factory()->createOne();

    expect($post->registerMediaConversions())->toBeNull();
});
