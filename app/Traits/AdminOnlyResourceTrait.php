<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait AdminOnlyResourceTrait
 * Aplica políticas de autorización para que solo usuarios con rol 'admin'
 * puedan acceder a los recursos de Filament
 */
trait AdminOnlyResourceTrait
{
    /**
     * Define las políticas de autorización para el recurso.
     * Solo usuarios con rol 'admin' pueden ver cualquier registro
     */
    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Solo usuarios con rol 'admin' pueden crear registros
     */
    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Solo usuarios con rol 'admin' pueden ver un registro específico
     */
    public static function canView(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Solo usuarios con rol 'admin' pueden editar registros
     */
    public static function canEdit(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Solo usuarios con rol 'admin' pueden eliminar registros
     */
    public static function canDelete(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Solo usuarios con rol 'admin' pueden eliminar múltiples registros
     */
    public static function canDeleteAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Solo usuarios con rol 'admin' pueden restaurar registros (si usas soft deletes)
     */
    public static function canRestore(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Solo usuarios con rol 'admin' pueden restaurar múltiples registros
     */
    public static function canRestoreAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Solo usuarios con rol 'admin' pueden forzar eliminación
     */
    public static function canForceDelete(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Solo usuarios con rol 'admin' pueden forzar eliminación múltiple
     */
    public static function canForceDeleteAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }
}
