<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    // Estados que cuentan como venta real (todo lo posterior al pago).
    private const PAID_STATUSES = ['PAID', 'PREPARING', 'SHIPPED', 'DELIVERED'];

    protected function getStats(): array
    {
        $revenue = $this->getRevenueStats();
        $orders = $this->getOrdersStats();
        $customers = $this->getCustomersStats();
        $pending = Order::where('status', 'PENDING')->count();

        return [
            Stat::make('Ingresos del Mes', '$' . number_format($revenue['current'], 0, ',', '.'))
                ->description($revenue['description'])
                ->descriptionIcon($revenue['icon'])
                ->color($revenue['color'])
                ->chart($revenue['chart']),

            Stat::make('Pedidos del Mes', $orders['current'])
                ->description($orders['description'])
                ->descriptionIcon($orders['icon'])
                ->color($orders['color'])
                ->chart($orders['chart']),

            Stat::make('Clientes Nuevos', $customers['current'])
                ->description($customers['description'])
                ->descriptionIcon($customers['icon'])
                ->color($customers['color'])
                ->chart($customers['chart']),

            Stat::make('Pedidos Pendientes de Pago', $pending)
                ->description($pending > 0 ? 'Esperando confirmación de pago' : 'Todo al día')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pending > 0 ? 'warning' : 'success'),
        ];
    }

    private function getRevenueStats(): array
    {
        $current = Order::whereIn('status', self::PAID_STATUSES)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $last = Order::whereIn('status', self::PAID_STATUSES)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        $diff = $current - $last;
        $increase = $diff >= 0;

        return [
            'current' => $current,
            'description' => $diff == 0 ? 'Sin cambios vs. mes anterior' : ($increase ? 'Aumento de $' . number_format(abs($diff), 0, ',', '.') : 'Disminución de $' . number_format(abs($diff), 0, ',', '.')),
            'icon' => $increase ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
            'color' => $increase ? 'success' : 'danger',
            'chart' => $this->lastDays(fn(Carbon $day) => Order::whereIn('status', self::PAID_STATUSES)
                ->whereDate('created_at', $day)
                ->sum('total_amount')),
        ];
    }

    private function getOrdersStats(): array
    {
        $current = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $last = Order::whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count();

        $diff = $current - $last;
        $increase = $diff >= 0;

        return [
            'current' => $current,
            'description' => $diff == 0 ? 'Igual al mes anterior' : ($increase ? abs($diff) . ' más que el mes pasado' : abs($diff) . ' menos que el mes pasado'),
            'icon' => $increase ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
            'color' => $increase ? 'success' : 'danger',
            'chart' => $this->lastDays(fn(Carbon $day) => Order::whereDate('created_at', $day)->count()),
        ];
    }

    private function getCustomersStats(): array
    {
        // "Clientes" son compradores reales (agrupados por email en la vista
        // customers), no la tabla de administradores del panel.
        $current = Customer::whereMonth('first_order_at', now()->month)
            ->whereYear('first_order_at', now()->year)
            ->count();
        $last = Customer::whereMonth('first_order_at', now()->subMonth()->month)
            ->whereYear('first_order_at', now()->subMonth()->year)
            ->count();

        $diff = $current - $last;
        $increase = $diff >= 0;

        return [
            'current' => $current,
            'description' => $diff == 0 ? 'Igual al mes anterior' : ($increase ? abs($diff) . ' más que el mes pasado' : abs($diff) . ' menos que el mes pasado'),
            'icon' => $increase ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
            'color' => $increase ? 'success' : 'danger',
            'chart' => $this->lastDays(fn(Carbon $day) => Customer::whereDate('first_order_at', $day)->count()),
        ];
    }

    /** Últimos 7 días, uno por uno, para el sparkline de cada tarjeta. */
    private function lastDays(callable $valueForDay): array
    {
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $values[] = (float) $valueForDay(now()->subDays($i));
        }

        return $values;
    }
}
