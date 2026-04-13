<?php

namespace App\Filament\Resources\StoreBannerResource\Pages;

use App\Filament\Resources\StoreBannerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoreBanner extends EditRecord
{
    protected static string $resource = StoreBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
