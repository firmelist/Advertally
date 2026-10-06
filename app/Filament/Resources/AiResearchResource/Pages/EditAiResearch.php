<?php

namespace App\Filament\Resources\AiResearchResource\Pages;

use App\Filament\Resources\AiResearchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAiResearch extends EditRecord
{
    protected static string $resource = AiResearchResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
