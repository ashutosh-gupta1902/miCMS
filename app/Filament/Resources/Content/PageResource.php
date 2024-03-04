<?php

namespace App\Filament\Resources\Content;

use App\Filament\Resources\Content\PageResource\Pages;
use App\Filament\Resources\Content\PageResource\RelationManagers;
use App\Models\Blog\Category;
use App\Models\Content\Page;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $slug = 'content/page';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationGroup = 'Pages';

    protected static ?string $navigationIcon = 'heroicon-s-document-text';
    protected static ?int $navigationSort = 2;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form

            ->schema([

                Section::make('Title of Page')
                    ->collapsible()
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->maxLength(255)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->unique(Page::class, 'slug', ignoreRecord: true),
                    ])->columns(2),
                Section::make('Content of Page')
                    ->collapsible()
                    ->schema([
                        MarkdownEditor::make('content')->required()->columnSpan('full'),
                    ]),
                Section::make('Details of Page')
                    ->collapsible()
                    ->schema([
                        ColorPicker::make('color')->required(),
                        TextInput::make('seo_title')
                            ->label('SEO Title'),
                        TextInput::make('seo_description')
                            ->label('SEO Description'),
                        Select::make('status')->options([
                            'Publish' => 'Publish',
                            'Future' => 'Future',
                            'Draft' => 'Draft',
                            'AutoDraft' => 'AutoDraft',
                            'Pending' => 'Pending',
                            'Private' => 'Private',
                            'Trash' => 'Trash',
                            'Inherit' => 'Inherit',
                        ]),
                        TagsInput::make('tags')
                            ->separator(',')
                            ->splitKeys(['Tab', ' '])
                            ->tagSuffix('%')
                            ->reorderable()
                            ->color('success')
                            ->nestedRecursiveRules([
                                'min:3',
                                'max:255',
                            ]),
                        Select::make('page_category_id')
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id')),
                    ])->columns(2),
                Section::make('Image of Page')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('thumbnail')->disk('public')->directory('thumbnails')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('content')
                    ->limit(20)
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('tags')
                    ->separator(',')
                    ->listWithLineBreaks()
                    ->limitList(1)
                    ->expandableLimitedList()
                    ->badge()
                    ->color('tags')
                    ->toggleable(),
                TextColumn::make('seo_title')
                    ->label('SEO Title')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('seo_description')
                    ->label('SEO Description')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Publish' => 'success',
                        'Future' => 'info',
                        'Draft' => 'draft',
                        'AutoDraft' => 'primary',
                        'Pending' => 'warning',
                        'Private' => 'orange',
                        'Trash' => 'danger',
                        'Inherit' => 'light',
                    })
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                ColorColumn::make('color')
                    ->toggleable(),
                ImageColumn::make('thumbnail')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
            ])
            ->filters([
                SelectFilter::make('Category')
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->options([
                        'Publish' => 'Publish',
                        'Future' => 'Future',
                        'Draft' => 'Draft',
                        'AutoDraft' => 'AutoDraft',
                        'Pending' => 'Pending',
                        'Private' => 'Private',
                        'Trash' => 'Trash',
                        'Inherit' => 'Inherit',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Listpages::route('/'),
            'create' => Pages\Createpage::route('/create'),
            'edit' => Pages\Editpage::route('/{record}/edit'),
        ];
    }
}
