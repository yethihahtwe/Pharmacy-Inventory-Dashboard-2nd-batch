<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Widgets\TableWidget as BaseWidget;

class NearestExpiry extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->heading('Top 5 Nearest Expiry')
            ->query(
                Transaction::query()
                    ->selectRaw('
                min(id) as id,
                item_id,
                category_id,
                package_form_id,
                exp_date,
                SUM(amount) as total_amount
                ')
                    ->groupBy('item_id', 'category_id', 'package_form_id', 'exp_date')
                    ->havingRaw('sum(amount) > 0')
                    ->whereNotNull('exp_date')
                    ->orderBy('exp_date', 'asc')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('item.name'),
                TextColumn::make('category.name'),
                TextColumn::make('exp_date')
                    ->badge()
                    ->color(function ($state) {
                        if ($state >= today()->addMonths(3)) {
                            return 'info';
                        } else if ($state < today()->addMonths(3) && $state >= today()->addMonth()) {
                            return 'warning';
                        } else {
                            return 'danger';
                        }
                    }),
                TextColumn::make('total_amount')
                    ->numeric(thousandsSeparator: ',')
                    ->alignEnd(),
                TextColumn::make('packageForm.name'),
            ])
            ->paginated(false);
    }
}
