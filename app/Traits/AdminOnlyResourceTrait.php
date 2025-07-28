<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

trait AdminOnlyResourceTrait
{
    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    public static function canView(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    public static function canDeleteAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    public static function canRestore(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    public static function canRestoreAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    public static function canForceDelete(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    public static function canForceDeleteAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }
}
