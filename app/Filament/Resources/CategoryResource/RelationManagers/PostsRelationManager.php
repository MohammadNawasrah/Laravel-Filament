<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make([
                // tel() for phone number validation
                // unique() for unique data value if database have already = new data will be add 
                // numeric() just for number
                // https://filamentphp.com/docs/3.x/forms/validation go to website to all validation functions
                TextInput::make("title"),
                ColorPicker::make("color"),
                TextInput::make("slug"),
                Select::make("category_id")->label("Category")->relationship("category","slug")
                ->searchable(),
                MarkdownEditor::make("content")->columnSpanFull(),
            ])->collapsible(true),

            FileUpload::make("thumbnail")->disk("public")->directory("thumbnails")->columnSpan(3),
            TagsInput::make("tags"),
            Toggle::make("published"),
        ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
