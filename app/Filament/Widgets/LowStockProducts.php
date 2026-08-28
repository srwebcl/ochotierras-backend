<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockProducts extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Stock Bajo';

    private const THRESHOLD = 10;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('is_active', true)
                    ->where('stock', '<', self::THRESHOLD)
                    ->orderBy('stock', 'asc')
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Producto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->color(fn(int $state): string => $state === 0 ? 'danger' : 'warning'),
            ])
            ->actions([
                Tables\Actions\Action::make('editar')
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->url(fn(Product $record): string => route('filament.admin.resources.products.edit', $record)),
            ])
            ->emptyStateHeading('Sin alertas de stock')
            ->emptyStateDescription('Ningún producto activo está por debajo de ' . self::THRESHOLD . ' unidades.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
