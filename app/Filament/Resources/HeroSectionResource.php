<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSectionResource\Pages;
use App\Models\HeroSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroSectionResource extends Resource
{
    protected static ?string $model = HeroSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Sitio Web';
    protected static ?string $navigationLabel = 'Banner Principal';
    protected static ?string $modelLabel = 'Portada';
    protected static ?string $pluralModelLabel = 'Portadas';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contenido Principal')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título Principal')
                            ->placeholder('VIÑA OCHOTIERRAS')
                            ->required(),
                        Forms\Components\TextInput::make('title_en')
                            ->label('Título Principal (Inglés)')
                            ->placeholder('OCHOTIERRAS WINERY'),

                        Forms\Components\TextInput::make('subtitle')
                            ->label('Subtítulo')
                            ->placeholder('En el corazón del Valle del Limarí')
                            ->required(),
                        Forms\Components\TextInput::make('subtitle_en')
                            ->label('Subtítulo (Inglés)')
                            ->placeholder('In the heart of Limari Valley'),
                    ]),

                Forms\Components\Section::make('Botones')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Group::make([
                                    Forms\Components\TextInput::make('button_primary_text')
                                        ->label('Texto Botón Primario')
                                        ->default('Nuestra Viña'),
                                    Forms\Components\TextInput::make('button_primary_text_en')
                                        ->label('Texto Botón Primario (Inglés)'),
                                ]),
                                Forms\Components\TextInput::make('button_primary_url')
                                    ->label('URL Botón Primario')
                                    ->default('/nosotros'),

                                Forms\Components\Group::make([
                                    Forms\Components\TextInput::make('button_secondary_text')
                                        ->label('Texto Botón Secundario')
                                        ->default('Tienda Online'),
                                    Forms\Components\TextInput::make('button_secondary_text_en')
                                        ->label('Texto Botón Secundario (Inglés)'),
                                ]),

                                Forms\Components\TextInput::make('button_secondary_url')
                                    ->label('URL Botón Secundario')
                                    ->default('/tienda'),
                            ]),
                    ]),

                Forms\Components\Section::make('Imágenes de Fondo')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('Imágenes del Slider')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                            ->maxSize(10240) // 10MB limit
                            ->multiple()
                            ->reorderable()
                            ->directory('hero-images')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtitle')
                    ->label('Subtítulo'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Activo'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Última actualización'),
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
            'index' => Pages\ListHeroSections::route('/'),
            'create' => Pages\CreateHeroSection::route('/create'),
            'edit' => Pages\EditHeroSection::route('/{record}/edit'),
        ];
    }
}
