<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function (DeleteAction $action) {
                    // Safety: an admin must not delete their own account from the panel.
                    if ($this->record->id === auth()->id()) {
                        Notification::make()
                            ->danger()
                            ->title('Не можеш да го избришеш сопствениот профил.')
                            ->send();
                        $action->cancel();
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Safety: prevent demoting the only remaining admin.
        if (
            $this->record->isAdmin()
            && $data['role'] !== 'admin'
            && \App\Models\User::where('role', 'admin')->count() <= 1
        ) {
            Notification::make()
                ->danger()
                ->title('Не можеш да го деградираш последниот администратор.')
                ->send();

            $data['role'] = 'admin';
        }

        return $data;
    }
}
