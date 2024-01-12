<?php

namespace MadeForYou\News\Resources\PostResource;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use MadeForYou\News\Resources\PostResource;

class EditPost extends EditRecord
{
    /**
     * @var class-string<PostResource>
     */
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::class,
            ForceDeleteAction::class,
            RestoreAction::class,
        ];
    }
}
