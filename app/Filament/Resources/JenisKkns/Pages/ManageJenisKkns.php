<?php

namespace App\Filament\Resources\JenisKkns\Pages;

use App\Filament\Resources\JenisKkns\JenisKknResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJenisKkns extends ManageRecords
{
    protected static string $resource = JenisKknResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
