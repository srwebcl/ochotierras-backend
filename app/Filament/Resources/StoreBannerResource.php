<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreBannerResource\Pages;
use App\Filament\Resources\StoreBannerResource\RelationManagers;
use App\Models\StoreBanner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreBannerResource extends Resource
{
    protected static ?string $model = StoreBanner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contenido')
                    ->schema([
                        Forms\Components\TextInput::make('pre_title')
                            ->label('Etiqueta Superior')
                            ->placeholder('Ej: Nueva Colección')
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->label('Título Principal')
                            ->required(),
                        Forms\Components\TextInput::make('highlighted_text')
                            ->label('Texto Destacado (Opcional)')
                            ->helperText('Escribe la palabra exacta del título que deseas resaltar en dorado.'),
                        Forms\Components\Textarea::make('subtitle')
                            ->label('Bajada / Subtítulo')
                            ->rows(2)
                            ->required(),
                    ]),

                Forms\Components\Section::make('Imágenes')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Imagen Desktop')
                            ->image()
                            ->directory('store-banners')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\FileUpload::make('mobile_image')
                            ->label('Imagen Móvil (Opcional)')
                            ->image()
                            ->directory('store-banners/mobile')
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make('Call to Action')
                    ->schema([
                        Forms\Components\TextInput::make('cta_text')
                            ->label('Texto del Botón')
                            ->placeholder('Ej: Comprar Ahora'),

                        // Link Builder
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('link_type_builder')
                                    ->label('Tipo de Enlace (Constructor)')
                                    ->options([
                                        'manual' => 'URL Manual',
                                        'wine' => 'Vino Individual',
                                        'pack' => 'Pack de Vinos',
                                        'category' => 'Categoría',
                                        'section' => 'Sección de la Tienda',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set) => $set('link_resource_id', null))
                                    ->dehydrated(false), // No guardar en BD

                                Forms\Components\Select::make('link_resource_id')
                                    ->label('Seleccionar Recurso')
                                    ->options(function (Get $get) {
                                        $type = $get('link_type_builder');
                                        if (!$type || $type === 'manual')
                                            return [];

                                        return match ($type) {
                                            'wine' => Product::where('is_pack', false)->pluck('name', 'slug'),
                                            'pack' => Product::where('is_pack', true)->pluck('name', 'slug'),
                                            'category' => Category::pluck('name', 'slug'),
                                            'section' => [
                                                'product-grid' => 'Nuestros Vinos',
                                                'packs' => 'Packs de Vinos',
                                            ],
                                            default => [],
                                        };
                                    })
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if (!$state)
                                            return;

                                        $type = $get('link_type_builder');
                                        $url = match ($type) {
                                            'wine' => '/tienda/' . $state,
                                            'pack' => '/tienda/pack/' . $state,
                                            'category' => '/tienda?category=' . $state,
                                            'section' => '/tienda#' . $state,
                                            default => null,
                                        };

                                        if ($url)
                                            $set('cta_link', $url);
                                    })
                                    ->dehydrated(false) // No guardar en BD
                                    ->disabled(fn(Get $get) => !$get('link_type_builder') || $get('link_type_builder') === 'manual'),

                                Forms\Components\TextInput::make('cta_link')
                                    ->label('Enlace Final (URL)')
                                    ->placeholder('https://...')
                                    ->helperText('Se genera automáticamente o puedes escribirlo manualmente.')
                                    ->required(fn(Get $get) => filled($get('cta_text'))),
                            ]),
                    ])->columns(1),

                Forms\Components\Section::make('Configuración')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagen'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pre_title')
                    ->label('Etiqueta'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Activo'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
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
            'index' => Pages\ListStoreBanners::route('/'),
            'create' => Pages\CreateStoreBanner::route('/create'),
            'edit' => Pages\EditStoreBanner::route('/{record}/edit'),
        ];
    }
}
