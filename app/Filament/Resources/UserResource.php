<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
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

    //  after heroicon-(o as outline or s as solid )-(svg icon name will be here)
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        // Here can create a input for Create new data && Update Data
        return $form
            ->schema([
                TextInput::make("name")->required(),
                TextInput::make("email")->email(),
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
                TextColumn::make("email")
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
