<?php

namespace App\Filament\Resources\AuditDimensionResource\Pages;

use App\Filament\Resources\AuditDimensionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditDimension extends EditRecord
{
    protected static string $resource = AuditDimensionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
