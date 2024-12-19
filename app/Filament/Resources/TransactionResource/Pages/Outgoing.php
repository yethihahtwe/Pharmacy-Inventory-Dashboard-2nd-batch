<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TransactionResource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Tables\Actions\Action as TableAction;

class Outgoing extends Page implements HasTable, HasForms
{
    use InteractsWithForms;
    use InteractsWithTable;
    protected static string $resource = TransactionResource::class;
    protected static string $view = 'filament.resources.transaction-resource.pages.outgoing';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back-action')
                ->label('Back to My Inventory')
                ->outlined()
                ->color('gray')
                ->url(fn(): string => ListTransactions::getUrl())
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // self::tableQuery()
                Transaction::query()
                    ->selectRaw('
        item_id,
        category_id,
        exp_date,
        batch,
        donor_id,
        SUM(amount) AS total_amount')
                    ->groupBy('item_id', 'category_id', 'exp_date', 'batch', 'donor_id')
                    ->havingRaw('SUM(amount) > 0')
            )
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
            ])
            ->filters(
                [
                    Filter::make('table-filter')
                        ->form([
                            TextInput::make('batch_filter')
                                ->placeholder('Search by batch number')
                                ->suffixAction(
                                    FormAction::make('clear')
                                        ->icon('heroicon-o-x-circle')
                                        ->action(fn(Set $set) => $set('batch_filter', ''))
                                        ->hidden(fn(Get $get) => $get('batch_filter') == '')
                                ),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['batch_filter'] ?? null,
                                    fn(Builder $query, $batch) => $query->where('batch', $batch)
                                );
                        }),
                ],
                layout: FiltersLayout::AboveContent
            )
            ->emptyStateHeading('No item for the above filter');
    }

    private function tableQuery()
    {
        return Transaction::query()
            ->selectRaw('
        item_id,
        category_id,
        exp_date,
        batch,
        donor_id,
        SUM(amount) AS total_amount')
            ->groupBy('item_id', 'category_id', 'exp_date', 'batch', 'donor_id')
            ->havingRaw('SUM(amount) > 0');
    }

    public function getTableRecordKey(Model $record): string
    {
        return uniqid();
    }
}