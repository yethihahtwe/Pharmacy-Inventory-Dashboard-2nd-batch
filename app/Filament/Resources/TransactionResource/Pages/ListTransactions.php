<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create Incoming Transactions')
            ->icon('heroicon-o-arrow-right-end-on-rectangle')
            ->outlined(),
            Actions\Action::make('outgoing')
            ->label('Create Outgoing Transactions')
            ->icon('heroicon-o-arrow-right-start-on-rectangle')
            ->url(Outgoing::getUrl())
            ->outlined(),
        ];
    }
}
