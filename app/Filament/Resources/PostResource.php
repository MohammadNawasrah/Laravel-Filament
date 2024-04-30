<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\AuthorsRelationManager;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make("Posts Info")->tabs([
                    Tab::make("Title")->schema([
                        Section::make([
                            // tel() for phone number validation
                            // unique() for unique data value if database have already = new data will be add 
                            // numeric() just for number
                            // https://filamentphp.com/docs/3.x/forms/validation go to website to all validation functions
                            TextInput::make("title"),
                            ColorPicker::make("color"),
                            TextInput::make("slug"),
                            Select::make("category_id")->label("Category")->relationship("category", "slug")
                                ->searchable(),
                            Toggle::make("published"),
                        ])->collapsible(true),
                    ])->icon('heroicon-o-arrow-up-on-square-stack'),
                    Tab::make("Content")->schema([
                        MarkdownEditor::make("content")->columnSpanFull(),
                    ])->icon("heroicon-o-document-text"),
                    Tab::make("Image")->schema([
                        FileUpload::make("thumbnail")->disk("public")->directory("thumbnails")->columnSpan(3),
                    ])->icon("heroicon-o-photo"),
                    Tab::make("Tags")->schema([
                        TagsInput::make("tags"),
                    ])->icon("heroicon-o-rectangle-stack")
                        ->iconPosition(IconPosition::Before),
                    Tab::make("Auther")->schema([
                        Section::make("Authors")->schema([
                            Select::make("authors")->relationship("authors", "name")->multiple(),
                        ])
                    ])->badge("HI")->icon("heroicon-o-user-group")->iconPosition(IconPosition::After)
                    // persistTabInQueryString:
                    // When you copy a link and open it in another browser window, the browser will open the link in a new tab, preserving the last tab you opened.
                    // By appending "?tab=-tags-tab" to the URL, this action ensures a better user experience by maintaining the currently active tab state.
                    // This is especially useful for users navigating through multiple tabs or sections of a web application.
                ])->activeTab(2)->persistTabInQueryString(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id")->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("title")->sortable()->searchable(),
                TextColumn::make("slug")->searchable(),
                TextColumn::make("category.slug"),
                ColorColumn::make("color"),
                ImageColumn::make("thumbnail"),
                CheckboxColumn::make("published")->inline(false),
                TextColumn::make("created_at")->label("Publised on")->date("Y M D"),
                TextColumn::make("updated_at")->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            AuthorsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
