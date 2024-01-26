<?php

namespace MadeForYou\News\Resources;

use Exception;
use Filament\Forms\Components\Builder as FormsBuilder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MadeForYou\FilamentContent\Facades\Content;
use MadeForYou\News\Models\Post;
use MadeForYou\News\Resources\PostResource\CreatePost;
use MadeForYou\News\Resources\PostResource\EditPost;
use MadeForYou\News\Resources\PostResource\ListPosts;
use MadeForYou\News\Resources\PostResource\ViewPost;

/**
 * Class PostResource
 *
 * This class represents a resource for managing blog posts.
 */
class PostResource extends Resource
{
    /**
     * The model class name for the resource.
     *
     * @var class-string<Post>|null
     */
    protected static ?string $model = Post::class;

    /**
     * The navigation icon for the resource.
     */
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    /**
     * Defines a form with the given parameters.
     *
     * @param  Form  $form  The form to configure.
     * @return Form The configured form.
     *
     * @throws Exception
     */
    public static function form(Form $form): Form
    {
        return $form->schema(components: [
            Section::make(heading: 'Bericht')
                ->description(description: 'Algemene informatie van het bericht')
                ->aside()
                ->columns(columns: [
                    'sm' => 1,
                ])
                ->schema(components: [
                    TextInput::make(name: 'title')
                        ->label(label: 'Titel')
                        ->required()
                        ->maxLength(length: 255)
                        ->string(),

                    Select::make(name: 'category_id')
                        ->label(label: 'Hoofdcategorie')
                        ->helperText(text: 'Dit is de hoofd categorie van dit '
                            . 'bericht. Aan de hand van deze categorie '
                            . 'wordt de url ook gegenereerd.')
                        ->nullable()
                        ->hidden(condition: ! config('filament-news.use_main_category'))
                        ->relationship(name: 'category', titleAttribute: 'name'),

                    Select::make(name: 'categories')
                        ->label(label: 'Categorieën')
                        ->helperText(text: 'Categorieën waar dit bericht bij hoort.')
                        ->multiple()
                        ->preload()
                        ->hidden(condition: ! config('filament-news.use_categories'))
                        ->relationship(name: 'categories', titleAttribute: 'name'),

                    DatePicker::make(name: 'date')
                        ->label(label: 'Datum')
                        ->nullable()
                        ->format(format: 'd-m-Y'),

                    Textarea::make(name: 'summary')
                        ->label(label: 'Korte introductie / samenvatting')
                        ->nullable()
                        ->string(),

                    SpatieMediaLibraryFileUpload::make(name: 'poster')
                        ->collection(collection: 'poster')
                        ->label(label: 'Poster afbeelding')
                        ->helperText(text: 'Afbeelding die in het overzicht wordt gebruikt van de nieuwsberichten.')
                        ->responsiveImages(),
                ]),

            Section::make(heading: 'Inhoud')
                ->description(description: 'Pagina inhoud van het bericht')
                ->columns(columns: [
                    'sm' => 1,
                ])
                ->collapsible()
                ->schema(components: [
                    FormsBuilder::make(name: 'content')
                        ->label('')
                        ->blocks(self::getContentBlocks())
                        ->reorderable()
                        ->collapsible(),
                ]),
        ]);
    }

    /**
     * Defines a table with the given parameters.
     *
     * @param  Table  $table  The table to configure.
     * @return Table The configured table.
     *
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('poster')
                    ->collection('poster')
                    ->conversion('preview'),

                TextColumn::make('title')
                    ->label('Titel'),

                TextColumn::make('date')
                    ->label('Datum'),

                TextColumn::make('category.name')
                    ->label('Hoofdcategorie')
                    ->hidden(! config('filament-news.use_main_category')),
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

    /**
     * Defines an info list with the given parameters.
     *
     * @param  Infolist  $infolist  The info list to configure.
     * @return Infolist  The configured info list.
     *
     * @throws Exception
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Grid::make([
                'sm' => 1,
            ])
                ->schema([
                    InfolistSection::make('Bericht')
                        ->description('Algemene informatie van het bericht')
                        ->aside()
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('title')
                                        ->label('Titel'),

                                    TextEntry::make('category.name')
                                        ->label('Hoofdcategorie')
                                        ->hidden(! config('filament-news.use_main_category')),

                                    TextEntry::make('categories.name')
                                        ->listWithLineBreaks()
                                        ->label('Categorieën')
                                        ->hidden(! config('filament-news.use_categories')),

                                    TextEntry::make('date')
                                        ->label('Datum')
                                        ->date('d-m-Y'),

                                    TextEntry::make('summary')
                                        ->label('Korte introductie / samenvatting'),
                                ]),
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
                ]),
        ]);
    }

    /**
     * Get the relations for the model.
     *
     * @return array The relations for the model.
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Returns an array of page routes and components.
     *
     * @return array The array of page routes and components.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
            'view' => ViewPost::route('/{record}'),
        ];
    }

    /**
     * Get the Eloquent query builder with global scopes removed.
     *
     * @return Builder The modified Eloquent query builder.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * Gathers the content block components from the configuration.
     */
    public static function getContentBlocks(): array
    {
        return collect(config('filament-news.content_blocks'))
            ->map(fn (string $block) => (new $block)->getBlock())
            ->toArray();
    }

    /**
     * Returns the navigation label for the "Berichten" page.
     *
     * @return string The navigation label.
     */
    public static function getNavigationLabel(): string
    {
        return 'Berichten';
    }

    /**
     * Returns the label for the model.
     *
     * @return string The label for the model.
     */
    public static function getModelLabel(): string
    {
        return 'Bericht';
    }

    /**
     * Get the plural label for the model.
     *
     * @return string The plural label for the model.
     */
    public static function getPluralModelLabel(): string
    {
        return 'Berichten';
    }

    /**
     * Retrieves the navigation group for the blog.
     *
     * @return string|null The navigation group for the blog, or null if not available.
     */
    public static function getNavigationGroup(): ?string
    {
        return 'Blog';
    }
}
