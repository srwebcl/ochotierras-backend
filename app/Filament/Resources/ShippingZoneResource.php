<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingZoneResource\Pages;
use App\Filament\Resources\ShippingZoneResource\RelationManagers;
use App\Models\ShippingZone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingZoneResource extends Resource
{
    protected static ?string $model = ShippingZone::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Tienda';
    protected static ?string $navigationLabel = 'Despachos';
    protected static ?string $modelLabel = 'Zona de Despacho';
    protected static ?string $pluralModelLabel = 'Zonas de Despacho';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre de Zona')
                    ->required()
                    ->placeholder('Ej: Zona Central, Extremo Sur'),

                Forms\Components\TextInput::make('price')
                    ->label('Costo de Envío')
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->default(0),

                Forms\Components\CheckboxList::make('regions')
                    ->label('Regiones Incluidas')
                    ->options([
                        'Arica y Parinacota' => 'Arica y Parinacota',
                        'Tarapacá' => 'Tarapacá',
                        'Antofagasta' => 'Antofagasta',
                        'Atacama' => 'Atacama',
                        'Coquimbo' => 'Coquimbo',
                        'Valparaíso' => 'Valparaíso',
                        'Metropolitana' => 'Metropolitana',
                        "O'Higgins" => "O'Higgins",
                        'Maule' => 'Maule',
                        'Ñuble' => 'Ñuble',
                        'Biobío' => 'Biobío',
                        'La Araucanía' => 'La Araucanía',
                        'Los Ríos' => 'Los Ríos',
                        'Los Lagos' => 'Los Lagos',
                        'Aysén' => 'Aysén',
                        'Magallanes' => 'Magallanes',
                    ])
                    ->columns(2)
                    ->gridDirection('row')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_free_shipping')
                    ->label('Envío Gratis')
                    ->reactive()
                    ->afterStateUpdated(fn($state, $set) => $state ? $set('price', 0) : null),

                Forms\Components\Toggle::make('is_active')
                    ->label('Activa')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Zona')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Costo')
                    ->money('CLP'),
                Tables\Columns\IconColumn::make('is_free_shipping')
                    ->label('Gratis')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListShippingZones::route('/'),
            'create' => Pages\CreateShippingZone::route('/create'),
            'edit' => Pages\EditShippingZone::route('/{record}/edit'),
        ];
    }
}
