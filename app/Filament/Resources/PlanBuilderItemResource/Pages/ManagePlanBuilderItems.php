<?php

namespace App\Filament\Resources\PlanBuilderItemResource\Pages;

use App\Filament\Resources\PlanBuilderItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePlanBuilderItems extends ManageRecords
{
    protected static string $resource = PlanBuilderItemResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
