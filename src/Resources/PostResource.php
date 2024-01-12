<?php

namespace MadeForYou\News\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MadeForYou\News\Models\Post;
use MadeForYou\News\Resources\PostResource\CreatePost;
use MadeForYou\News\Resources\PostResource\EditPost;
use MadeForYou\News\Resources\PostResource\ListPosts;

class PostResource extends Resource
{
    /**
     * @var class-string<Post>
     */
    protected static ?string $model = Post::class;

    /**
     * The navigation icon for the resource.
     */
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    /**
     * Create a form using the given object.
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Bericht')
                ->description('Algemene informatie van het bericht')
                ->aside()
                ->columns([
                    'sm' => 1
                ])
                ->schema([
                    TextInput::make('title')
                        ->label('Titel')
                        ->required()
                        ->maxLength(255)
                        ->string(),

                    Select::make('categories')
                        ->label('Categorie')
                        ->helperText('Categorieën waar dit bericht bij hoort.')
                        ->multiple()
                        ->relationship(name: 'categories', titleAttribute: 'name'),

                    DatePicker::make('date')
                        ->label('Datum')
                        ->nullable()
                        ->maxLength(255)
                        ->string()
                        ->format('d-m-Y'),

                    Textarea::make('summary')
                        ->label('Korte introductie / samenvatting')
                        ->nullable()
                        ->string(),

                    // @todo content toevoegen
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titel'),

                TextColumn::make('date')
                    ->label('Datum'),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Grid::make([
                'sm' => 1,
            ])
                ->schema([
                    InfolistSection::make('Bericht')
                        ->description('Algemene informatie van het bericht')
                        ->schema([
                            TextEntry::make('title')
                                ->label('Titel'),

                            TextEntry::make('date')
                                ->label('Datum')
                                ->date('d-m-Y'),

                            TextEntry::make('summary')
                                ->label('Korte introductie / samenvatting'),
                        ]),

                    InfolistSection::make('Administratie')
                        ->description('Belangrijke gegevens voor de ontwikkelaars van de categorie.')
                        ->aside()
                        ->schema([
                            TextEntry::make('id')
                                ->label('ID')
                                ->numeric(),

                            TextEntry::make('created_at')
                                ->label('Aangemaakt op')
                                ->dateTime(),

                            TextEntry::make('updated_at')
                                ->label('Laatst gewijzigd op')
                                ->since(),

                            TextEntry::make('deleted_at')
                                ->label('Verwijderd op')
                                ->dateTime(),
                        ]),
                ])
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationLabel(): string
    {
        return 'Berichten';
    }

    public static function getModelLabel(): string
    {
        return 'Bericht';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Berichten';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Blog';
    }

}
