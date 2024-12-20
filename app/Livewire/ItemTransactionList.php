<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ItemTransactionList extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;
    public int $itemId;

    public function mount($itemId)
    {
        $this->itemId = $itemId;
    }

    public function render()
    {
        return view('livewire.item-transaction-list');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(self::tableQuery())
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('item.name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('packageForm.name')
                    ->searchable(),
                TextColumn::make('exp_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('batch')
                    ->searchable(),
                TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('donor.name')
                    ->searchable(),
                TextColumn::make('sourceWarehouse.name')
                    ->searchable(),
                TextColumn::make('destinationWarehouse.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }

    private function tableQuery()
    {
        return Transaction::query()
            ->where('item_id', $this->itemId);
    }
}
