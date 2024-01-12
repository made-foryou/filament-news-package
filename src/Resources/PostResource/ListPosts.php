<?php

namespace MadeForYou\News\Resources\PostResource;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use MadeForYou\News\Resources\PostResource;

class ListPosts extends ListRecords
{
    /**
     * @var class-string<PostResource>
     */
    protected static string $resource = PostResource::class;

    /**
     * Defining the header actions which will be showed in the header
     * on the list page.
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
