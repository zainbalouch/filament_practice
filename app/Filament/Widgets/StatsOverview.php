<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    // General Stats Section
    protected function getGeneralStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('Total products in the system')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart([7, 3, 4, 5, 6, 3, 5])
                ->color('success'),

            Stat::make('Total Orders', Order::count())
                ->description('All orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([4, 5, 3, 7, 4, 5, 3])
                ->color('info'),

            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->chart([3, 5, 4, 3, 6, 3, 5])
                ->color('warning'),

            Stat::make('Total Categories', Category::count())
                ->description('Product categories')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->chart([5, 3, 4, 5, 3, 5, 4])
                ->color('success'),
        ];
    }

    // Order Stats Section
    protected function getOrderStats(): array
    {
        return [
            Stat::make('Pending Orders', Order::where('status', 'pending')->count())
                ->description('Pending orders')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([4, 5, 3, 7, 4, 5, 3])
                ->color('warning'),

            Stat::make('Processing Orders', Order::where('status', 'processing')->count())
                ->description('Processing orders')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->chart([4, 5, 3, 7, 4, 5, 3])
                ->color('info'),

            Stat::make('Shipped Orders', Order::where('status', 'shipped')->count())
                ->description('Shipped orders')
                ->descriptionIcon('heroicon-m-truck')
                ->chart([4, 5, 3, 7, 4, 5, 3])
                ->color('primary'),

            Stat::make('Delivered Orders', Order::where('status', 'delivered')->count())
                ->description('Delivered orders')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([4, 5, 3, 7, 4, 5, 3])
                ->color('success'),

            Stat::make('Cancelled Orders', Order::where('status', 'cancelled')->count())
                ->description('Cancelled orders')
                ->descriptionIcon('heroicon-m-x-circle')
                ->chart([4, 5, 3, 7, 4, 5, 3])
                ->color('danger'),
        ];
    }

    protected function getStats(): array
    {
        return [
            ...array_map(fn ($stat) => $stat->extraAttributes([
                'class' => 'ring-2 ring-gray-200',
            ]), $this->getGeneralStats()),

            Stat::make('', '')
                ->extraAttributes([
                    'class' => 'col-span-full h-px bg-gray-200 dark:bg-gray-700 my-4',
                ]),

            ...array_map(fn ($stat) => $stat->extraAttributes([
                'class' => 'ring-2 ring-gray-200',
            ]), $this->getOrderStats()),
        ];
    }

    protected static ?int $columns = 12;
}
