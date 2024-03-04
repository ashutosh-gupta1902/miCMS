<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\PostResource\Pages;
use App\Filament\Resources\Blog\PostResource\RelationManagers;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $slug = 'blog/post';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-s-document-text';
    protected static ?int $navigationSort = 0;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Title of Post')
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
                            ->unique(Post::class, 'slug', ignoreRecord: true),
                    ])->columns(2),
                Section::make('Content of Post')
                    ->collapsible()
                    ->schema([
                        MarkdownEditor::make('content')->required()->columnSpan('full'),
                    ]),
                Section::make('Details of Post')
                    ->collapsible()
                    ->schema([
                        ColorPicker::make('color')->required(),
                        Select::make('category_id')
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id')),
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
                    ])->columns(2),
                Section::make('Image Of Post')
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
                    ->limit(30)
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
                TextColumn::make('category.name')
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
                        'Private' => 'dark',
                        'Trash' => 'danger',
                        'Inherit' => 'light',
                    })
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                ColorColumn::make('color')
                    ->toggleable(),
                ImageColumn::make('thumbnail'),
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
            'index' => Pages\Listposts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
