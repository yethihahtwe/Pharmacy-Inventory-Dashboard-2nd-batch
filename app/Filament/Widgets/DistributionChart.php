<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class DistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distributed Warehouses';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        $transactionsWithDistributions = Transaction::query()
            ->selectRaw('
            destination,
            SUM(amount) as total_amount
            ')
            ->groupBy('destination')
            ->where('type', 'OUT')
            ->orderBy('total_amount', 'desc')
            ->get();

        foreach ($transactionsWithDistributions as $transaction) {
            $data[] = $transaction->total_amount;
            $labels[] = $transaction->destinationWarehouse->name;
        }
        return [
            'datasets' => [
                [
                    'label' => 'Distributed Warehouses',
                    'data' => $data,
                    'borderWidth' => 1,
                    'backgroundColor' => [
                        'rgba(255, 99, 71, 0.2)',   // Tomato
                        'rgba(255, 165, 0, 0.2)',   // Orange
                        'rgba(255, 215, 0, 0.2)',   // Gold
                        'rgba(255, 69, 0, 0.2)',    // Red-Orange
                        'rgba(255, 140, 0, 0.2)',   // Dark Orange
                        'rgba(255, 127, 80, 0.2)',  // Coral
                        'rgba(255, 99, 71, 0.2)',   // Tomato (repeated for consistency)
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 71, 1)',     // Tomato
                        'rgba(255, 165, 0, 1)',     // Orange
                        'rgba(255, 215, 0, 1)',     // Gold
                        'rgba(255, 69, 0, 1)',      // Red-Orange
                        'rgba(255, 140, 0, 1)',     // Dark Orange
                        'rgba(255, 127, 80, 1)',    // Coral
                        'rgba(255, 99, 71, 1)',     // Tomato (repeated for consistency)
                    ],
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false]
            ],
        ];
    }
}
