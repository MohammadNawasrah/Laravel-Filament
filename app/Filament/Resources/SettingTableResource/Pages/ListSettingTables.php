<?php

namespace App\Filament\Resources\SettingTableResource\Pages;

use App\Filament\Resources\SettingTableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSettingTables extends ListRecords
{
    protected static string $resource = SettingTableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
