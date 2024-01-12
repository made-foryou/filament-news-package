<?php

namespace MadeForYou\News\Resources\PostResource;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;
use MadeForYou\News\Resources\PostResource;

class ViewPost extends ViewRecord
{
    /**
     * @var class-string<PostResource>
     */
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
