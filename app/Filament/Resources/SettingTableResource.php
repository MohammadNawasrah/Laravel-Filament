<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingTableResource\Pages;
use App\Filament\Resources\SettingTableResource\RelationManagers;
use App\Models\Setting;
use App\Models\SettingTable;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingTableResource extends Resource
{
    protected static ?string $model = Setting::class;

    public static ?string $modelLabel = 'Settings Table';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $arrayOfOrder = [];
        for ($i = 1; $i <= 100; $i++) {
            $arrayOfOrder[$i] = $i;
        }
        foreach (Setting::all("order_data")->toArray() as $key => $value) {
            unset($arrayOfOrder[$value["order_data"]]);
        }
        return $form
            ->schema([
                TextInput::make("lable")->visibleOn("create"),
                TextInput::make("value")->visibleOn("create"),
                Select::make("type")->options([
                    "text" => "Text",
                    "list" => "List",
                    "bool" => "Boolean",
                ])->visibleOn("create"),
                Select::make("order_data")->options($arrayOfOrder),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("lable"),
                TextColumn::make("type"),
                TextColumn::make("order_data"),
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
            'index' => Pages\ListSettingTables::route('/'),
            'create' => Pages\CreateSettingTable::route('/create'),
            'edit' => Pages\EditSettingTable::route('/{record}/edit'),
        ];
    }
}
