<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = bcrypt('password');
        $data['isActive'] = 0;
        $data['firstLogin'] = true;
        return $data;
    }

    protected function afterCreate(): void
    {
        $user = $this->record;
        $token = app('auth.password.broker')->createToken($user);
        $notification = new \Filament\Notifications\Auth\ResetPassword($token);
        $notification->url = \Filament\Facades\Filament::getResetPasswordUrl($token, $user);
        $user->notify($notification);
    }
}
