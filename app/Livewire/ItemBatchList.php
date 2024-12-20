<?php

namespace App\Livewire;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
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
                TextColumn::make('packageForm.name')
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
                TextColumn::make('sourceWarehouse.name')
                    ->searchable(),
            ])
            ->actions([
                Action::make('dispense')
                    ->form([
                        Section::make('Item Details')
                            ->schema([
                                Placeholder::make('item')
                                    ->content(fn($record) => $record->item->name)
                                    ->inlineLabel(),
                                Placeholder::make('category')
                                    ->content(fn($record) => $record->category->name)
                                    ->inlineLabel(),
                                Placeholder::make('package form')
                                    ->content(fn($record) => $record->packageForm->name)
                                    ->inlineLabel(),
                                Placeholder::make('expiry date')
                                    ->content(fn($record) => $record->exp_date)
                                    ->inlineLabel(),
                                Placeholder::make('batch number')
                                    ->content(fn($record) => $record->batch)
                                    ->inlineLabel(),
                                Placeholder::make('donor')
                                    ->content(fn($record) => $record->donor->name)
                                    ->inlineLabel(),
                                Placeholder::make('source')
                                    ->content(fn($record) => $record->sourceWarehouse->name)
                                    ->inlineLabel(),
                            ])->columns(2),
                        Section::make('Dispense Details')
                            ->schema([
                                DatePicker::make('date')
                                    ->label('Date of Dispense')
                                    ->required()
                                    ->native(false),
                                TextInput::make('amount')
                                    ->label('Amount')
                                    ->required()
                                    ->numeric()
                                    ->maxValue(fn($record) => $record->total_amount),
                                Select::make('destination')
                                    ->label('Destination')
                                    ->required()
                                    ->placeholder('Please select destination')
                                    ->native(false)
                                    ->relationship(
                                        name: 'destinationWarehouse',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn(Builder $query) => $query->where('name', '!=', 'Direct Purchase')
                                    )
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Destination Warehouse')
                                            ->required(),
                                    ]),
                                Textarea::make('remarks')
                            ])->columns(2),
                    ])
                    ->action(function (array $data, Transaction $record) {
                        $data['type'] = 'OUT';
                        $data['item_id'] = $record->item_id;
                        $data['category_id'] = $record->category_id;
                        $data['package_form_id'] = $record->package_form_id;
                        $data['exp_date'] = $record->exp_date;
                        $data['batch'] = $record->batch;
                        $data['donor_id'] = $record->donor_id;
                        $data['source'] = $record->source;
                        $data['amount'] *= -1;
                        $record->create($data);
                        redirect()->route('filament.user.pages.my-inventory');
                    })
            ]);
    }

    private function tableQuery(): Builder
    {
        $itemId = $this->itemId;
        return Transaction::query()
            ->selectRaw('
            min(id) AS id,
            item_id,
            category_id,
            package_form_id,
            exp_date,
            batch,
            SUM(amount) AS total_amount,
            donor_id,
            source')
            ->where('item_id', '=', $itemId)
            ->groupBy('item_id', 'category_id', 'package_form_id', 'exp_date', 'batch', 'donor_id', 'source')
            ->havingRaw('SUM(amount) > 0')
            ->orderBy('exp_date', 'ASC');
    }
}
