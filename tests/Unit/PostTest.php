<?php

use MadeForYou\Categories\Models\Category;
use MadeForYou\News\Models\Post;

use function Pest\Laravel\assertDatabaseHas;

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
        'title' => 'test'
    ]);

    $post->category()->associate($category);

    $post->save();

    assertDatabaseHas('made_posts', [
        'title' => 'test',
        'category_id' => $category->id,
    ]);

    expect($post->category->id)->toBe($category->id);
});
