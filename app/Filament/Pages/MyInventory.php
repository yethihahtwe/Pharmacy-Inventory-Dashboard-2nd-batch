<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class MyInventory extends Page implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.my-inventory';

    public function table(Table $table): Table
    {
        return $table
            ->query(self::tableQuery())
            ->columns([
                TextColumn::make('item.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->numeric(thousandsSeparator: ',')
                    ->alignEnd()
                    ->badge()
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->url(function ($record) {
                        return ItemDetail::getUrl([
                            'itemId' => $record->item_id,
                            'itemName' => $record->item->name,
                            'itemCategory' => $record->category->name,
                            'itemTotalAmount' => $record->total_amount,
                        ]);
                    }),
            ]);
    }

    private static function tableQuery(): Builder
    {
        return Transaction::query()
            ->selectRaw('item_id, category_id, SUM(amount) as total_amount')
            ->havingRaw('SUM(amount) > 0')
            ->groupBy('item_id', 'category_id')
            ->orderBy('item_id');
    }

    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }
}
