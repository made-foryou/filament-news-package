<?php

namespace MadeForYou\News\Resources\PostResource;

use Filament\Resources\Pages\CreateRecord;
use MadeForYou\News\Resources\PostResource;

class CreatePost extends CreateRecord
{
    /**
     * @var class-string<PostResource>
     */
    protected static string $resource = PostResource::class;
}
