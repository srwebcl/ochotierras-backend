<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Solo lectura: son personas que compraron (o intentaron comprar), derivadas
 * de `orders`. No existe una cuenta de cliente que crear/editar acá —
 * ver App\Models\Customer.
 */
class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Clientes';
    protected static ?string $modelLabel = 'Cliente';
    protected static ?string $pluralModelLabel = 'Clientes';

    protected static ?string $navigationGroup = 'Ventas';
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('last_order_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono'),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Pedidos')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Total Comprado')
                    ->money('CLP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_order_at')
                    ->label('Primer Pedido')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_order_at')
                    ->label('Último Pedido')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
