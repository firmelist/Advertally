<?php

namespace App\Filament\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Role gate for Filament resources.
 * Define `protected static array $roles = ['admin', 'editor'];` on the resource.
 */
trait RestrictsToRoles
{
    protected static function roleAllowed(): bool
    {
        $user = auth()->user();

        return $user && in_array($user->role, static::$roles ?? ['admin'], true);
    }

    public static function canViewAny(): bool
    {
        return static::roleAllowed();
    }

    public static function canView(Model $record): bool
    {
        return static::roleAllowed();
    }

    public static function canCreate(): bool
    {
        return static::roleAllowed();
    }

    public static function canEdit(Model $record): bool
    {
        return static::roleAllowed();
    }

    public static function canDelete(Model $record): bool
    {
        return static::roleAllowed() && auth()->user()?->isAdmin();
    }

    public static function canDeleteAny(): bool
    {
        return static::roleAllowed() && auth()->user()?->isAdmin();
    }
}
