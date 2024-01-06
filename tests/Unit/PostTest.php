<?php

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
