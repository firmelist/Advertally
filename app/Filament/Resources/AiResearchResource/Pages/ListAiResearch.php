<?php

namespace App\Filament\Resources\AiResearchResource\Pages;

use App\Filament\Resources\AiResearchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAiResearch extends ListRecords
{
    protected static string $resource = AiResearchResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
