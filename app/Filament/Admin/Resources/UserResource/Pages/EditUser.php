<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\ProfileDetails;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            // Actions\ForceDeleteAction::make(),
            // Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->getRecord();

        if ($user && $user->hasBusinessProfile() && !$user->profileDetails) {
            $user->setRelation('profileDetails', new ProfileDetails());
        }

        if ($user && $user->profileDetails) {
            $data['profile_status'] = $user->profileDetails->status;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = $this->getRecord();

        if ($user && $user->hasBusinessProfile() && isset($data['profile_status'])) {
            if ($user->profileDetails) {
                $user->profileDetails->update([
                    'status' => $data['profile_status']
                ]);
            } else {
                ProfileDetails::create([
                    'user_id' => $user->id,
                    'status' => $data['profile_status'],
                ]);
            }
        }

        unset($data['profile_status']);

        return $data;
    }
}
