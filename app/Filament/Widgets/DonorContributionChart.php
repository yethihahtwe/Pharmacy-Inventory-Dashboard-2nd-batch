<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class DonorContributionChart extends ChartWidget
{
    protected static ?string $heading = 'Donor Contribution';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        $transactionsWithDonors = Transaction::query()
            ->selectRaw('
            donor_id,
            SUM(amount) as total_amount
            ')
            ->groupBy('donor_id')
            ->where('type', 'IN')
            ->orderBy('total_amount', 'desc')
            ->get();

        foreach ($transactionsWithDonors as $transaction) {
            $data[] = $transaction->total_amount;
            $labels[] = $transaction->donor->name;
        }
        return [
            'datasets' => [
                [
                    'label' => 'Donor contribution',
                    'data' => $data,
                    'borderWidth' => 1,
                    'backgroundColor' => [
                        'rgba(70, 130, 180, 0.7)',
                        'rgba(0, 128, 128, 0.7)',
                        'rgba(100, 149, 237, 0.7)',
                        'rgba(0, 206, 209, 0.7)',
                        'rgba(106, 90, 205, 0.7)',
                        'rgba(72, 209, 204, 0.7)',
                        'rgba(65, 105, 225, 0.7)',
                    ],
                    'borderColor' => [
                        'rgba(70, 130, 180, 1)',
                        'rgba(0, 128, 128, 1)',
                        'rgba(100, 149, 237, 1)',
                        'rgba(0, 206, 209, 1)',
                        'rgba(106, 90, 205, 1)',
                        'rgba(72, 209, 204, 1)',
                        'rgba(65, 105, 225, 1)',
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
