<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Últimos Pedidos';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest('created_at'))
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('N° Pedido'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('CLP'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match (strtoupper($state)) {
                        'PENDING' => 'gray',
                        'PAID' => 'success',
                        'PREPARING' => 'warning',
                        'SHIPPED' => 'info',
                        'DELIVERED' => 'success',
                        'FAILED' => 'danger',
                        'CANCELLED' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match (strtoupper($state)) {
                        'PENDING' => 'Pendiente',
                        'PAID' => 'Pagado',
                        'PREPARING' => 'En Preparación',
                        'SHIPPED' => 'Enviado',
                        'DELIVERED' => 'Entregado',
                        'FAILED' => 'Fallido',
                        'CANCELLED' => 'Cancelado',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Order $record): string => route('filament.admin.resources.orders.edit', $record)),
            ]);
    }
}
