<?php

namespace App\Filament\Resources;

use App\Console\Commands\ListFilamentResources;
use App\Filament\Resources\UserPermissionResource\Pages;
use App\Filament\Resources\UserPermissionResource\RelationManagers;
use App\Models\Action;
use App\Models\UserPermission;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class UserPermissionResource extends Resource
{
    protected static ?string $model = UserPermission::class;

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
                Select::make('user_id')->relationship("user", "email")
                    ->searchable()->preload()->required(),
                Select::make("page_name")
                    ->options(self::getAllFilesInDirectory())
                    ->required()->searchable()->preload()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, $set) => $set('actions', [])),
                Select::make("actions")->options(function ($get) {
                    $pageName = $get('page_name');
                    if (empty($pageName)) {
                        return [];
                    }

                    $actions = Action::query()->whereNull('pages_name')
                    ->orWhereJsonLength('pages_name', '=', 0)
                    ->orWhereJsonContains('pages_name', $pageName)
                        ->pluck('slug', 'slug');

                    return $actions->toArray();
                })
                    ->searchable()->preload()->required()->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("user.email"),
                TextColumn::make("page_name"),
                TextColumn::make("actions"),
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
            'index' => Pages\ListUserPermissions::route('/'),
            'create' => Pages\CreateUserPermission::route('/create'),
            'edit' => Pages\EditUserPermission::route('/{record}/edit'),
        ];
    }
}
