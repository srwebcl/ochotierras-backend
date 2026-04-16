<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Productos';
    protected static ?string $modelLabel = 'Producto';
    protected static ?string $pluralModelLabel = 'Productos';

    protected static ?string $navigationGroup = 'Tienda';
    protected static ?int $navigationSort = 1;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Detalles del Producto')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Toggle::make('is_pack')
                                            ->label('Es un Pack')
                                            ->helperText('Activa esto si el producto se compone de otros vinos.')
                                            ->live()
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('name')
                                            ->label('Nombre del Producto')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true),
                                    ])->columns(2),

                                Forms\Components\Textarea::make('description')
                                    ->label('Descripción Comercial')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('price')
                                            ->label('Precio (Valor Caja/Pack)')
                                            ->required()
                                            ->numeric()
                                            ->prefix('$'),
                                        Forms\Components\TextInput::make('stock')
                                            ->label('Stock Disponible')
                                            ->required()
                                            ->numeric()
                                            ->default(0),
                                        Forms\Components\TextInput::make('sku')
                                            ->label('SKU')
                                            ->unique(ignoreRecord: true),
                                        Forms\Components\TextInput::make('units_per_box')
                                            ->label('Unidades por Caja')
                                            ->numeric()
                                            ->default(6)
                                            ->required(),
                                    ])->columns(2),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Producto Activo / Visible')
                                    ->default(true),
                            ]),

                        Forms\Components\Tabs\Tab::make('Multimedia')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Imagen Principal')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                                    ->directory('products')
                                    ->columnSpanFull(),

                                Forms\Components\FileUpload::make('gallery')
                                    ->label('Galería de Imágenes')
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                                    ->directory('products/gallery')
                                    ->columnSpanFull(),

                                Forms\Components\FileUpload::make('technical_sheet')
                                    ->label('Ficha Técnica (PDF)')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->directory('technical-sheets')
                                    ->downloadable()
                                    ->columnSpanFull(),

                                Forms\Components\ColorPicker::make('accent_color')
                                    ->label('Color de Acento'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Ficha Técnica & Enología')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('vintage_year')
                                            ->label('Año Cosecha')
                                            ->numeric()
                                            ->placeholder('Ej: 2019'),
                                        Forms\Components\TextInput::make('strain')
                                            ->label('Cepa / Variedad')
                                            ->placeholder('Ej: Cabernet Sauvignon'),
                                        Forms\Components\TextInput::make('origin')
                                            ->label('Valle / Origen')
                                            ->placeholder('Ej: Valle del Limarí'),
                                        Forms\Components\TextInput::make('technical_details.aging_potential')
                                            ->label('Potencial de Guarda')
                                            ->placeholder('Ej: 10 a 12 años'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Análisis Químico')
                                    ->description('Datos técnicos del vino (Guardados en JSON)')
                                    ->schema([
                                        Forms\Components\KeyValue::make('technical_details.analysis')
                                            ->label('Parámetros')
                                            ->keyLabel('Parámetro (Ej: Alcohol)')
                                            ->valueLabel('Valor (Ej: 13.5°)')
                                            ->addActionLabel('Agregar Parámetro'),
                                    ]),

                                Forms\Components\RichEditor::make('technical_details.tasting_notes')
                                    ->label('Notas de Cata')
                                    ->columnSpanFull(),

                                Forms\Components\Repeater::make('technical_details.awards')
                                    ->label('Premios y Reconocimientos')
                                    ->schema([
                                        Forms\Components\TextInput::make('award')
                                            ->label('Premio')
                                            ->required(),
                                    ])
                                    ->addActionLabel('Agregar Premio')
                                    ->defaultItems(0)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Contenido Pack / Caja Mixta')
                            ->visible(fn(Forms\Get $get) => $get('is_pack'))
                            ->schema([
                                Forms\Components\Repeater::make('bundleConnections')
                                    ->relationship()
                                    ->label('Vinos en el Pack')
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('Vino')
                                            ->options(Product::where('is_pack', false)->pluck('name', 'id'))
                                            ->required()
                                            ->searchable()
                                            ->reactive() // Make it reactive to update label or other fields if needed
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('quantity')
                                            ->label('Cantidad')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required()
                                            ->columnSpan(1),
                                    ])
                                    ->itemLabel(fn(array $state): ?string => Product::find($state['product_id'] ?? null)?->name ?? 'Seleccionar Vino')
                                    ->visible(fn(Forms\Get $get) => $get('is_pack'))
                                    ->defaultItems(0)
                                    ->addActionLabel('Agregar Vino al Pack')
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Clasificación')
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('Categoría / Línea')
                                    ->relationship('category', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->hidden(fn(Forms\Get $get) => $get('is_pack')) // Hide for packs
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn(Forms\Set $set, $state) => $set('slug', Str::slug($state))),
                                        Forms\Components\TextInput::make('slug')->required(),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Traducción (Inglés)')
                            ->icon('heroicon-m-language')
                            ->schema([
                                Forms\Components\TextInput::make('name_en')
                                    ->label('Nombre del Producto (EN)')
                                    ->required(false),

                                Forms\Components\Textarea::make('description_en')
                                    ->label('Descripción Comercial (EN)')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('short_description_en')
                                    ->label('Descripción Corta / Colección (EN)')
                                    ->columnSpanFull(),

                                Forms\Components\Section::make('Ficha Técnica (EN)')
                                    ->schema([
                                        Forms\Components\RichEditor::make('tasting_notes_en')
                                            ->label('Notas de Cata (EN)')
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('pairing_en')
                                            ->label('Maridaje (EN)')
                                            ->rows(2),

                                        Forms\Components\TextInput::make('service_temp_en')
                                            ->label('Temperatura de Servicio (EN)'),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO Google')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Título Meta (SEO)')
                                    ->maxLength(60)
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('generateSEO')
                                            ->icon('heroicon-m-sparkles')
                                            ->tooltip('Generar SEO con IA')
                                            ->action(function (Forms\Get $get, Forms\Set $set) {
                                                $name = $get('name');
                                                $desc = $get('description');

                                                if (!$name) {
                                                    \Filament\Notifications\Notification::make()
                                                        ->title('Falta información')
                                                        ->body('Ingresa el nombre del producto primero.')
                                                        ->warning()
                                                        ->send();
                                                    return;
                                                }

                                                $apiKey = env('GEMINI_API_KEY');
                                                if (!$apiKey) {
                                                    \Filament\Notifications\Notification::make()
                                                        ->title('Falta API Key')
                                                        ->body('Configura GEMINI_API_KEY en el archivo .env')
                                                        ->danger()
                                                        ->send();
                                                    return;
                                                }

                                                try {
                                                    $prompt = "Actúa como experto SEO. Crea un Meta Title (max 60 chars) y Meta Description (max 160 chars) para un vino llamado '{$name}'. Descripción: '{$desc}'. Devuelve JSON: {\"meta_title\": \"...\", \"meta_description\": \"...\"}";

                                                    /** @var \Illuminate\Http\Client\Response $response */
                                                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                                                        'Content-Type' => 'application/json',
                                                    ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                                                                'contents' => [['parts' => [['text' => $prompt]]]]
                                                            ]);

                                                    $result = $response->json();
                                                    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                                                        $content = $result['candidates'][0]['content']['parts'][0]['text'];
                                                        $content = str_replace(['```json', '```'], '', $content);
                                                        $data = json_decode($content, true);

                                                        if ($data) {
                                                            $set('meta_title', $data['meta_title'] ?? '');
                                                            $set('meta_description', $data['meta_description'] ?? '');
                                                            \Filament\Notifications\Notification::make()
                                                                ->title('SEO Generado')
                                                                ->success()
                                                                ->send();
                                                        }
                                                    }
                                                } catch (\Exception $e) {
                                                    \Filament\Notifications\Notification::make()->title('Error')->body($e->getMessage())->danger()->send();
                                                }
                                            })
                                    ),
                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Descripción Meta (SEO)')
                                    ->maxLength(160),
                            ]),
                        Forms\Components\Tabs\Tab::make('Colección (Opcional)')
                            ->schema([
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Destacado en Colección')
                                    ->default(false),
                                Forms\Components\TextInput::make('subtitle')
                                    ->label('Subtítulo'),
                                Forms\Components\TextInput::make('type')
                                    ->label('Tipo'),
                                Forms\Components\Textarea::make('featured_description')
                                    ->label('Descripción Corta')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagen'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Product $record): string => $record->subtitle ?? ''),
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('CLP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->color(fn(string $state): string => $state < 10 ? 'danger' : 'success')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Colección')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
