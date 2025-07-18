<?php

namespace App\Filament\Admin\Resources\PropertyResource\Pages;

use App\Filament\Admin\Resources\PropertyResource;
use App\Models\Property;
use App\Notifications\PropertyStatusUpdated; 
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification; 
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\Log; 

class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;
 
    protected static ?string $title = 'Editar Propiedad';

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    /**
     *
     * 
     *
     * @param Model 
     * @param array 
     * @return Model 
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
      
        $oldStatus = $record->status;

       
        $record->update($data);


        if ($oldStatus !== $record->status) {
            $newStatus = $record->status;
            $adminNotes = $record->admin_notes; 

            $notificationTitle = '';
            $notificationBody = '';
            $notificationType = 'info';

            switch ($newStatus) {
                case Property::STATUS_PUBLISHED:
                    $notificationTitle = '¡Propiedad Publicada!';
                    $notificationBody = 'Tu propiedad "' . ($record->address->full_address ?? 'Sin dirección') . '" ha sido publicada exitosamente.';
                    $notificationType = 'success';
                    
                    $record->published_at = now();
                    $record->approved_at = now();
                    $record->rejected_at = null; 
                    break;
                case Property::STATUS_REJECTED:
                    $notificationTitle = 'Propiedad Rechazada';
                    $notificationBody = 'Tu propiedad "' . ($record->address->full_address ?? 'Sin dirección') . '" ha sido rechazada. Revisa las notas del administrador.';
                    $notificationType = 'danger';
                    $record->rejected_at = now();
                    $record->published_at = null; 
                    $record->approved_at = null; 
                    break;
                case Property::STATUS_INACTIVE:
                    $notificationTitle = 'Propiedad Inactiva';
                    $notificationBody = 'Tu propiedad "' . ($record->address->full_address ?? 'Sin dirección') . '" ha sido marcada como inactiva.';
                    $notificationType = 'warning';
                    $record->published_at = null;
                    $record->approved_at = null;
                    $record->rejected_at = null;
                    break;
                case Property::STATUS_SOLD:
                    $notificationTitle = 'Propiedad Vendida';
                    $notificationBody = 'Tu propiedad "' . ($record->address->full_address ?? 'Sin dirección') . '" ha sido marcada como vendida.';
                    $notificationType = 'success';
                    $record->published_at = null; 
                    $record->approved_at = null;
                    $record->rejected_at = null;
                    break;
                case Property::STATUS_RENTED:
                    $notificationTitle = 'Propiedad Rentada';
                    $notificationBody = 'Tu propiedad "' . ($record->address->full_address ?? 'Sin dirección') . '" ha sido marcada como rentada.';
                    $notificationType = 'success';
                    $record->published_at = null; 
                    $record->approved_at = null;
                    $record->rejected_at = null;
                    break;
                case Property::STATUS_PENDING_REVIEW:
                    $notificationTitle = 'Propiedad en Revisión';
                    $notificationBody = 'Tu propiedad "' . ($record->address->full_address ?? 'Sin dirección') . '" está ahora en revisión.';
                    $notificationType = 'info';
                    $record->published_at = null;
                    $record->approved_at = null;
                    $record->rejected_at = null;
                    break;
                default:
                  
                    return $record;
            }

            
            $record->save();

           
            if ($record->user) {
                try {
                 
                    $record->user->notify(new PropertyStatusUpdated($record, $adminNotes));
                    Log::info('Notificación de cambio de estado enviada al usuario desde el formulario de edición', [
                        'property_id' => $record->id,
                        'user_id' => $record->user->id,
                        'new_status' => $newStatus
                    ]);


                    Notification::make()
                        ->title($notificationTitle)
                        ->body('Notificación enviada al anunciante.')
                        ->icon('heroicon-o-bell')
                        ->color($notificationType)
                        ->send();
                } catch (\Exception $e) {
                    Log::error('Error al enviar notificación de cambio de estado de propiedad desde el formulario de edición', [
                        'error' => $e->getMessage(),
                        'property_id' => $record->id,
                        'user_id' => $record->user->id ?? 'N/A',
                        'new_status' => $newStatus
                    ]);
                
                    Notification::make()
                        ->title('Error al enviar notificación al anunciante')
                        ->body('La propiedad se actualizó, pero hubo un error al enviar la notificación al anunciante: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            } else {
                Log::warning('Usuario no encontrado para la propiedad al intentar enviar notificación desde el formulario de edición', ['property_id' => $record->id]);
                Notification::make()
                    ->title('Advertencia: Usuario no encontrado')
                    ->body('La propiedad se actualizó, pero no se pudo enviar la notificación porque el usuario propietario no fue encontrado.')
                    ->warning()
                    ->send();
            }
        }

        return $record; 
    }
}
