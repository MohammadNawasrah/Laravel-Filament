<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionResource\Pages;
use App\Filament\Resources\ActionResource\RelationManagers;
use App\Models\Action;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ActionResource extends Resource
{
    protected const PAGE_NAME="action";
    protected static ?string $model = Action::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    static function getAllFilesInDirectory()
    {
        // Get all files (excluding directories) from the specified directories
        $filesInResources = File::files(app_path('Filament/Resources'));
        $filesInPages = File::files(app_path('Filament/Pages'));

        // Merge the two arrays
        $files = array_merge($filesInResources, $filesInPages);

        // Extract filenames without the "Resource" suffix as key and value
        $filenames = collect($files)->flatMap(function ($file) {
            // Get the filename from the path
            $filename = pathinfo($file, PATHINFO_FILENAME);
            // Remove the "Resource" suffix from the filename
            $filenameWithoutResource = str_replace('Resource', '', $filename);
            // Convert the filename to lowercase
            $lowercaseFilename = strtolower($filenameWithoutResource);
            return [$lowercaseFilename => $lowercaseFilename];
        })->toArray();

        return $filenames;
    }
    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('name')->unique(ignoreRecord:true)->required()->live(onBlur: true)->afterStateUpdated(
                    function (string $operation,$state, Set $set) {
                        $set("slug", Str::slug($state));
                    }
                ),
                TextInput::make('slug')->unique(ignoreRecord:true)->readOnly()->required(),
                Select::make("pages_name")->options(self::getAllFilesInDirectory())->searchable()->preload()
                ->multiple()
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('slug'),
                TextColumn::make('pages_name'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListActions::route('/'),
            'create' => Pages\CreateAction::route('/create'),
            'edit' => Pages\EditAction::route('/{record}/edit'),
        ];
    }
}
