<?php

namespace App\Filament\Resources\MataKuliahs\Pages;

use App\Filament\Resources\MataKuliahs\MataKuliahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMataKuliahs extends ManageRecords
{
    protected static string $resource = MataKuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
