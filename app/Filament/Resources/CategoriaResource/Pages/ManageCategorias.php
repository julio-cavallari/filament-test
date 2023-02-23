<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use Filament\Pages\Actions;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\CategoriaResource;

class ManageCategorias extends ManageRecords
{
    protected static string $resource = CategoriaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->successNotification(
            Notification::make()
                    ->success()
                    ->title('Categoria criada com sucesso!')
                    ->body('A categoria foi criada com sucesso!')
                    ->actions([
                        Action::make('Ok')
                            ->button()
                            ->close(),
                        Action::make('Desfazer')
                            ->color('secondary')
                            ->emit('undoEditingCategoria')
                            ->close(),
                    ]),
            ),
            Actions\EditAction::make()->successNotification(
            Notification::make()
                    ->success()
                    ->title('Categoria atualizada com sucesso!')
                    ->body('A categoria foi atualizada com sucesso!')
                    ->actions([
                        Action::make('Ok')
                            ->button()
                            ->close(),
                        Action::make('Desfazer')
                            ->color('secondary')
                            ->emit('undoEditingCategoria')
                            ->close(),
                    ]),
            ),
        ];
    }
}
