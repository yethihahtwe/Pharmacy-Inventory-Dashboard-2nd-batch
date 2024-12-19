<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Filament\Forms\Set;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('item_id')
                    ->required()
                    ->relationship(name: 'item', titleAttribute: 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\Select::make('category_id')
                            ->required()
                            ->relationship(name: 'category', titleAttribute: 'name')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                            ])
                            ->createOptionModalHeading('Create New Category'),
                    ])
                    ->createOptionModalHeading('Create New Item')
                    ->placeholder('please select item')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('category_id', Item::find($state)->category->id ?? null);
                    }),
                Forms\Components\Select::make('category_id')
                    ->required()
                    ->relationship(name: 'category', titleAttribute: 'name')
                    ->placeholder('please select item first')
                    ->disabled()
                    ->dehydrated(true)
                    ->native(false),
                Forms\Components\Select::make('package_form_id')
                    ->required()
                    ->relationship(name: 'packageForm', titleAttribute: 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                    ])
                    ->createOptionModalHeading('Create New Package Form')
                    ->placeholder('please select package form')
                    ->native(false),
                Forms\Components\DatePicker::make('exp_date')
                    ->label('Expiry Date')
                    ->native(false),
                Forms\Components\TextInput::make('batch')
                    ->label('Batch number')
                    ->placeholder('please enter batch number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->placeholder('please enter amount')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\Select::make('donor_id')
                    ->required()
                    ->relationship(name: 'donor', titleAttribute: 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                    ])
                    ->createOptionModalHeading('Create New Donor')
                    ->placeholder('please select donor')
                    ->native(false),
                Forms\Components\Select::make('source')
                    ->required()
                    ->relationship(name: 'sourceWarehouse', titleAttribute: 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                    ])
                    ->createOptionModalHeading('Create New Source')
                    ->placeholder('please select source')
                    ->native(false),
                Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('exp_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('donor.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sourceWarehouse.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('destination')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
            'outgoing' => Pages\Outgoing::route('/outgoing'),
        ];
    }
}
