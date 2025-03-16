<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders by Status';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $orders = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orders->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#fbbf24', // pending (yellow)
                        '#3b82f6', // processing (blue)
                        '#8b5cf6', // shipped (purple)
                        '#22c55e', // delivered (green)
                        '#ef4444', // cancelled (red)
                    ],
                ],
            ],
            'labels' => $orders->pluck('status')->map(fn ($status) => ucfirst($status))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
