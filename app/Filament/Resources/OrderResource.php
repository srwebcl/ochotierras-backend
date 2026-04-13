<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationLabel = 'Pedidos';
    protected static ?string $modelLabel = 'Pedido';
    protected static ?string $pluralModelLabel = 'Pedidos';
    protected static ?string $navigationGroup = 'Tienda';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Información del Cliente')
                            ->schema([
                                Forms\Components\TextInput::make('customer_name')
                                    ->label('Nombre Cliente')
                                    ->required(),
                                Forms\Components\TextInput::make('customer_email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('customer_phone')
                                    ->label('Teléfono')
                                    ->tel()
                                    ->required(),
                                Forms\Components\Textarea::make('shipping_address')
                                    ->label('Dirección de Envío')
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Section::make('Detalle de Productos')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->label('Items del Pedido')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('Producto')
                                            ->relationship('product', 'name')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn($state, $set) => $set('unit_price', \App\Models\Product::find($state)?->price ?? 0)),
                                        Forms\Components\TextInput::make('quantity')
                                            ->label('Cantidad')
                                            ->numeric()
                                            ->default(1)
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn($state, $get, $set) => $set('total', $state * $get('unit_price'))),
                                        Forms\Components\TextInput::make('unit_price')
                                            ->label('Precio Unitario')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required(),
                                        Forms\Components\TextInput::make('total')
                                            ->label('Subtotal')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required(),
                                    ])
                                    ->columns(4)
                            ])
                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Estado del Pedido')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        'pending' => 'Pendiente',
                                        'paid' => 'Pagado',
                                        'preparing' => 'En Preparación',
                                        'shipped' => 'Enviado',
                                        'delivered' => 'Entregado',
                                        'cancelled' => 'Cancelado',
                                        'failed' => 'Fallido',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('total_amount')
                                    ->label('Monto Total')
                                    ->prefix('$')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('courier_name')
                                    ->label('Courier / Transporte'),
                                Forms\Components\TextInput::make('tracking_number')
                                    ->label('N° Seguimiento'),
                            ])
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('N° Pedido')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'paid' => 'success',
                        'preparing' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'failed' => 'danger',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'preparing' => 'En Preparación',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregado',
                        'failed' => 'Fallido',
                        'cancelled' => 'Cancelado',
                        default => $state,
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('CLP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('Imprimir')
                    ->icon('heroicon-o-printer')
                    ->action(function (Order $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo '<html><head><title>Pedido #' . $record->id . '</title></head><body style="font-family: sans-serif; padding: 20px;">';
                            echo '<div style="text-align: center; margin-bottom: 20px;">';
                            echo '<h1>Comprobante de Pedido #' . $record->id . '</h1>';
                            echo '<p>OCHOTIERRAS</p>';
                            echo '</div>';

                            echo '<p><strong>Cliente:</strong> ' . $record->customer_name . '</p>';
                            echo '<p><strong>Email:</strong> ' . $record->customer_email . '</p>';
                            echo '<p><strong>Fecha:</strong> ' . $record->created_at->format('d/m/Y H:i') . '</p>';
                            echo '<p><strong>Estado:</strong> ' . ucfirst($record->status) . '</p>';

                            echo '<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">';
                            echo '<tr style="background: #eee;">';
                            echo '<th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Producto</th>';
                            echo '<th style="padding: 10px; text-align: right; border: 1px solid #ddd;">Cant.</th>';
                            echo '<th style="padding: 10px; text-align: right; border: 1px solid #ddd;">Precio</th>';
                            echo '<th style="padding: 10px; text-align: right; border: 1px solid #ddd;">Total</th>';
                            echo '</tr>';

                            foreach ($record->items as $item) {
                                echo '<tr>';
                                echo '<td style="padding: 10px; border: 1px solid #ddd;">' . $item->product->name . '</td>';
                                echo '<td style="padding: 10px; text-align: right; border: 1px solid #ddd;">' . $item->quantity . '</td>';
                                echo '<td style="padding: 10px; text-align: right; border: 1px solid #ddd;">$' . number_format($item->unit_price, 0, ',', '.') . '</td>';
                                echo '<td style="padding: 10px; text-align: right; border: 1px solid #ddd;">$' . number_format($item->total, 0, ',', '.') . '</td>';
                                echo '</tr>';
                            }

                            echo '</table>';
                            echo '<h3 style="text-align: right; margin-top: 20px;">Total: $' . number_format($record->total_amount, 0, ',', '.') . '</h3>';
                            echo '<script>window.print();</script>';
                            echo '</body></html>';
                        }, 'pedido-' . $record->id . '.html');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
