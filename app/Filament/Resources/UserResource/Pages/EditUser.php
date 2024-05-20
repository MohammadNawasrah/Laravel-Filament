<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        $action = [
            Actions\DeleteAction::make(),
        ];
        return  auth()->user()->type == User::ROLE_ADMIN ? $action : [];
    }
}
