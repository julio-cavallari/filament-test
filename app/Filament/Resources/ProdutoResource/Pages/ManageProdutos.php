<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageProdutos extends ManageRecords
{
    protected static string $resource = ProdutoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->successNotification(
                Notification::make()
                     ->success()
                     ->title('Produto criado com sucesso!')
                     ->body('O produto foi criado com sucesso!'),
             ),
        ];
    }
}
