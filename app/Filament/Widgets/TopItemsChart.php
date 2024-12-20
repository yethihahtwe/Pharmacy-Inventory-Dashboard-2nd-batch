<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class TopItemsChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Stocked Items';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        $transactionsWithItems = Transaction::query()
            ->selectRaw('
            item_id,
            category_id,
            SUM(amount) as total_amount
            ')
            ->groupBy('item_id', 'category_id')
            ->havingRaw('SUM(amount) > 0')
            ->orderBy('total_amount', 'desc')
            ->limit(10)
            ->get();

        foreach ($transactionsWithItems as $transaction) {
            $data[] = $transaction->total_amount;
            $labels[] = $transaction->item->name . ' (' . $transaction->category->name . ')';
        }

        return [
            'datasets' => [
                [
                    'label' => 'Most Stocked Items',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(70, 130, 180, 0.7)',   // Steel Blue
                        'rgba(0, 128, 128, 0.7)',    // Teal
                        'rgba(100, 149, 237, 0.7)',  // Cornflower Blue
                        'rgba(0, 206, 209, 0.7)',    // Dark Turquoise
                        'rgba(106, 90, 205, 0.7)',   // Slate Blue
                        'rgba(72, 209, 204, 0.7)',   // Medium Turquoise
                        'rgba(65, 105, 225, 0.7)',   // Royal Blue
                        'rgba(30, 144, 255, 0.7)',   // Dodger Blue
                        'rgba(32, 178, 170, 0.7)',   // Light Sea Green
                        'rgba(95, 158, 160, 0.7)',   // Cadet Blue
                    ],
                    'borderColor' => [
                        'rgba(70, 130, 180, 1)',     // Steel Blue
                        'rgba(0, 128, 128, 1)',      // Teal
                        'rgba(100, 149, 237, 1)',    // Cornflower Blue
                        'rgba(0, 206, 209, 1)',      // Dark Turquoise
                        'rgba(106, 90, 205, 1)',     // Slate Blue
                        'rgba(72, 209, 204, 1)',     // Medium Turquoise
                        'rgba(65, 105, 225, 1)',     // Royal Blue
                        'rgba(30, 144, 255, 1)',     // Dodger Blue
                        'rgba(32, 178, 170, 1)',     // Light Sea Green
                        'rgba(95, 158, 160, 1)',     // Cadet Blue
                    ],
                    'borderWidth' => 1
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
