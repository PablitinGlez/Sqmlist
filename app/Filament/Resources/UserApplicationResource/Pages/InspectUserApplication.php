<?php

namespace App\Filament\Resources\UserApplicationResource\Pages;

use App\Filament\Resources\UserApplicationResource;
use App\Models\UserApplication;
use App\Models\ProfileDetails;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Notifications\UserApplicationStatusUpdated;

class InspectUserApplication extends EditRecord
{
    protected static string $resource = UserApplicationResource::class;
    protected static ?string $title = 'Inspeccionar Solicitud de Perfil';

    protected $originalStatus;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Volver a la Lista')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->originalStatus = $this->record->status;

        if ($data['status'] === UserApplication::STATUS_APPROVED && empty($data['status_message'])) {
            $userTypeLabel = UserApplication::TYPE_OPTIONS[$this->record->requested_user_type] ?? 'tu perfil';
            $data['status_message'] = "¡Felicidades! Tu solicitud para ser '$userTypeLabel' ha sido aprobada y tu perfil está ahora activo.";
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $currentStatus = $this->record->status;
        $user = $this->record->user;
        $requestedUserType = $this->record->requested_user_type;

        $statusWasChangedFromPending = ($this->originalStatus === UserApplication::STATUS_PENDING) &&
            ($currentStatus !== UserApplication::STATUS_PENDING);

        
        if ($statusWasChangedFromPending && in_array($currentStatus, [UserApplication::STATUS_APPROVED, UserApplication::STATUS_REJECTED])) {
            $this->sendUserNotification($user, $currentStatus);
        }

    
        if ($statusWasChangedFromPending) {
            if ($currentStatus === UserApplication::STATUS_APPROVED) {
                $this->handleApproval($user, $requestedUserType);
            } elseif ($currentStatus === UserApplication::STATUS_REJECTED) {
                $this->handleRejection($user, $requestedUserType);
            }
        }
    }

    protected function sendUserNotification($user, $status): void
    {
        try {
            if ($user) {
                $user->notify(new UserApplicationStatusUpdated($this->record));
                Notification::make()
                    ->title('Notificación enviada')
                    ->body("Se ha enviado una notificación al usuario sobre el estado de su solicitud.")
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al enviar notificación')
                ->body('No se pudo enviar la notificación al usuario: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function handleApproval($user, $requestedUserType): void
    {
        if (!$user) {
            Notification::make()
                ->title('Error: Usuario no encontrado')
                ->body('No se pudo encontrar el usuario asociado a la solicitud.')
                ->danger()
                ->send();
            return;
        }

       
        $role = match ($requestedUserType) {
            UserApplication::TYPE_OWNER => 'owner',
            UserApplication::TYPE_AGENT => 'agent',
            UserApplication::TYPE_REAL_ESTATE_COMPANY => 'real_estate_company',
            default => null,
        };

        if ($role) {
            $user->assignRole($role);
        }

        
        $this->createOrUpdateProfile($user, $requestedUserType);

        Notification::make()
            ->title('Solicitud aprobada')
            ->body("El perfil de {$this->getUserTypeLabel($requestedUserType)} ha sido creado y el rol asignado.")
            ->success()
            ->send();
    }

    protected function handleRejection($user, $requestedUserType): void
    {
        if (!$user) {
            Notification::make()
                ->title('Error: Usuario no encontrado')
                ->body('No se pudo encontrar el usuario asociado a la solicitud.')
                ->danger()
                ->send();
            return;
        }

        
        $role = match ($requestedUserType) {
            UserApplication::TYPE_OWNER => 'owner',
            UserApplication::TYPE_AGENT => 'agent',
            UserApplication::TYPE_REAL_ESTATE_COMPANY => 'real_estate_company',
            default => null,
        };

        if ($role) {
            $user->removeRole($role);
        }

       
        $this->deleteProfileIfExists($user);

        Notification::make()
            ->title('Solicitud rechazada')
            ->body("La solicitud para el perfil de {$this->getUserTypeLabel($requestedUserType)} ha sido rechazada.")
            ->warning()
            ->send();
    }

    protected function createOrUpdateProfile($user, $requestedUserType): void
    {
        $profileData = [
            'user_id' => $user->id,
            'role_type' => $requestedUserType,
            'phone_number' => $this->record->phone_number,
            'whatsapp_number' => $this->record->whatsapp_number,
            'contact_email' => $this->record->contact_email,
            'identification_type' => $this->record->identification_type,
            'identification_path' => $this->record->identification_path,
            'license_path' => $this->record->license_path,
            'years_experience' => $this->record->years_experience,
            'real_estate_company' => $this->record->real_estate_company,
            'rfc' => $this->record->rfc,
            'status' => 'active',
            'approved_at' => now(),
        ];

    
        ProfileDetails::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );
    }

    protected function deleteProfileIfExists($user): void
    {
        $user->profileDetails()->delete();
    }

    protected function getUserTypeLabel($userType): string
    {
        return UserApplication::TYPE_OPTIONS[$userType] ?? $userType;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
