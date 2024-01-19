<?php

namespace MadeForYou\News\Resources\CategoryResource\RelationManagers;

use Exception;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use MadeForYou\News\Models\Post;

class PostRelationManager extends RelationManager
{
    /**
     * The defined relationship on the model.
     *
     * @var string $relationship
     */
    protected static string $relationship = 'posts';

    /**
     * Generates a table with specific settings and components.
     *
     * @param  Table  $table  The table object to configure.
     *
     * @throws Exception
     * @return Table The configured table object.
     */
    public function table(Table $table): Table
    {
        return $table->heading('Nieuwsberichten')
            ->description('De nieuwsberichten die deze categorie als hoofdcategorie hebben.')
            ->recordTitleAttribute('title')
            ->columns([
                SpatieMediaLibraryImageColumn::make('poster')
                    ->collection('poster')
                    ->conversion('preview'),

                TextColumn::make('title')
                    ->label('Titel')
                    ->description(fn (Post $post): ?string => $post->summary),

                TextColumn::make('updated_at')
                    ->label('Laatst gewijzigd op')
                    ->since(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
