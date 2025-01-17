<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Faker\Provider\ar_EG\Text;
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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup="User";

    //  after heroicon-(o as outline or s as solid )-(svg icon name will be here)
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        // Here can create a input for Create new data && Update Data
        return $form
            ->schema([
                TextInput::make("name")->required(),
                TextInput::make("email")->email(),
                Select::make("type")->options([
                    User::ROLE_USER => User::ROLE_USER,
                    User::ROLE_ADMIN => User::ROLE_ADMIN,
                    User::ROLE_EDITOR => User::ROLE_EDITOR,
                ])->required(),
                // readOnly use to let password without return it data from database
                // visibleOn use to let input visible just on create user , when use update user 
                // will be hidden
                TextInput::make("password")->password()->visibleOn("create")
            ]);
    }

    public static function table(Table $table): Table
    {
        // Here Data table for all user
        return $table
            ->columns([
                // !must be like data in database inside make function
                TextColumn::make("id"),
                TextColumn::make("name"),
                TextColumn::make("email"),
                TextColumn::make("type")->badge()->color(
                    function (string $state): string {
                        if($state===User::ROLE_ADMIN) return "info";
                        if($state===User::ROLE_EDITOR) return "success";
                        return "";
                    }
                )
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
