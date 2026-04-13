<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $revenueDetails = $this->getRevenueStats();
        $ordersDetails = $this->getOrdersStats();
        $customersDetails = $this->getCustomersStats();

        return [
            Stat::make('Ingresos del Mes', '$' . number_format($revenueDetails['current'], 0, ',', '.'))
                ->description($revenueDetails['description'])
                ->descriptionIcon($revenueDetails['icon'])
                ->color($revenueDetails['color'])
                ->chart($revenueDetails['chart']),

            Stat::make('Nuevos Pedidos', $ordersDetails['current'])
                ->description($ordersDetails['description'])
                ->descriptionIcon($ordersDetails['icon'])
                ->color($ordersDetails['color'])
                ->chart($ordersDetails['chart']),

            Stat::make('Nuevos Clientes', $customersDetails['current'])
                ->description($customersDetails['description'])
                ->descriptionIcon($customersDetails['icon'])
                ->color($customersDetails['color'])
                ->chart($customersDetails['chart']),
        ];
    }

    private function getRevenueStats(): array
    {
        $currentMonth = \App\Models\Order::whereIn('status', ['paid', 'preparing', 'shipped', 'delivered'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $lastMonth = \App\Models\Order::whereIn('status', ['paid', 'preparing', 'shipped', 'delivered'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        $diff = $currentMonth - $lastMonth;
        $increase = $diff > 0;

        return [
            'current' => $currentMonth,
            'description' => $diff == 0 ? 'Sin cambios' : ($increase ? 'Aumento de $' . number_format(abs($diff), 0, ',', '.') : 'Disminución de $' . number_format(abs($diff), 0, ',', '.')),
            'icon' => $increase ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
            'color' => $increase ? 'success' : 'danger',
            'chart' => [10, 15, 20, 18, 25, 30, $currentMonth > 0 ? 35 : 0], // Dummy chart
        ];
    }

    private function getOrdersStats(): array
    {
        $current = \App\Models\Order::whereMonth('created_at', now()->month)->count();
        $last = \App\Models\Order::whereMonth('created_at', now()->subMonth()->month)->count();

        $diff = $current - $last;
        $increase = $diff >= 0;

        return [
            'current' => $current,
            'description' => $diff == 0 ? 'Igual al mes anterior' : ($increase ? abs($diff) . ' más que el mes pasado' : abs($diff) . ' menos que el mes pasado'),
            'icon' => $increase ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
            'color' => $increase ? 'success' : 'danger',
            'chart' => [5, 8, 3, 12, 6, $current], // Dummy chart
        ];
    }

    private function getCustomersStats(): array
    {
        $current = \App\Models\User::whereMonth('created_at', now()->month)->count();
        $last = \App\Models\User::whereMonth('created_at', now()->subMonth()->month)->count();

        $diff = $current - $last;
        $increase = $diff >= 0;

        return [
            'current' => $current,
            'description' => $diff == 0 ? 'Igual al mes anterior' : ($increase ? abs($diff) . ' más que el mes pasado' : abs($diff) . ' menos que el mes pasado'),
            'icon' => $increase ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
            'color' => $increase ? 'success' : 'danger',
            'chart' => [2, 1, 5, 2, $current], // Dummy chart
        ];
    }
}
