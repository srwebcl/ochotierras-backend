<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * OJO: este modelo es la tabla de autenticación del panel (App\Models\User),
 * no una lista de clientes de la tienda — cualquier fila acá con un correo
 * @ochotierras.cl puede loguearse en /admin (ver User::canAccessPanel()).
 * Los clientes reales viven en CustomerResource (derivados de los pedidos).
 *
 * Cualquier usuario del panel puede VER esta lista (solo lectura) — para
 * saber quién tiene acceso. Crear, editar (contraseña incluida) y eliminar
 * cuentas es exclusivo de Super Admin (ver los can*() al final). Cualquier
 * usuario puede cambiar su PROPIA contraseña desde su perfil (menú de
 * usuario, arriba a la derecha) sin necesitar este acceso — ver ->profile()
 * en AdminPanelProvider.
 */
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Usuarios del Panel';
    protected static ?string $modelLabel = 'Usuario del Panel';
    protected static ?string $pluralModelLabel = 'Usuarios del Panel';

    protected static ?string $navigationGroup = 'Sistema';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->description('Cualquier cuenta con correo @ochotierras.cl puede acceder al panel — usar solo para el equipo, nunca para clientes.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        // Solo tiene sentido al crear o editar (acciones ya
                        // restringidas a Super Admin) — al ver un registro no
                        // se muestra, no hay nada que "ver": nunca se rellena
                        // con la contraseña real, solo permite escribir una
                        // nueva.
                        Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->revealable()
                            ->visible(fn(string $operation): bool => in_array($operation, ['create', 'edit']))
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->helperText(fn(string $operation): string => $operation === 'edit' ? 'Dejar en blanco para no cambiarla.' : ''),
                        Forms\Components\Toggle::make('is_super_admin')
                            ->label('Super Admin')
                            ->helperText('Puede ver, editar y eliminar cualquier cuenta del panel (incluida esta sección). Dáselo solo a quien realmente lo necesite.')
                            ->disabled(fn(string $operation, ?Model $record): bool => $operation === 'view' || ($record !== null && $record->is(auth()->user())))
                            ->hint(fn(?Model $record): ?string => ($record !== null && $record->is(auth()->user())) ? 'No podés quitarte este permiso a vos mismo.' : null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_super_admin')
                    ->label('Super Admin')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn(): bool => (bool) auth()->user()?->is_super_admin),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(): bool => (bool) auth()->user()?->is_super_admin)
                    ->disabled(fn(User $record): bool => $record->is(auth()->user())),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->visible(fn(): bool => (bool) auth()->user()?->is_super_admin),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        // Cualquier usuario logueado al panel puede VER la lista.
        return true;
    }

    public static function canView(Model $record): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return (bool) auth()->user()?->is_super_admin;
    }

    public static function canEdit(Model $record): bool
    {
        return (bool) auth()->user()?->is_super_admin;
    }

    public static function canDelete(Model $record): bool
    {
        return (bool) auth()->user()?->is_super_admin;
    }

    public static function canDeleteAny(): bool
    {
        return (bool) auth()->user()?->is_super_admin;
    }
}
