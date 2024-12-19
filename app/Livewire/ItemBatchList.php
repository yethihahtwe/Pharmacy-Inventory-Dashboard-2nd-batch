<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ItemBatchList extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public int $itemId;

    public function mount($itemId)
    {
        $this->itemId = $itemId;
    }
    public function render()
    {
        return view('livewire.item-batch-list');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(self::tableQuery())
            ->columns([
                TextColumn::make('item.name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('exp_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('batch')
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('donor.name')
                    ->searchable(),
            ]);
    }

    private function tableQuery(): Builder
    {
        $itemId = $this->itemId;
        return Transaction::query()
            ->selectRaw('
            item_id,
            category_id,
            exp_date,
            batch,
            SUM(amount) AS total_amount,
            donor_id')
            ->where('item_id', '=', $itemId)
            ->groupBy('item_id', 'category_id', 'exp_date', 'batch', 'donor_id')
            ->havingRaw('SUM(amount) > 0')
            ->orderBy('exp_date', 'ASC');
    }

    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }
}