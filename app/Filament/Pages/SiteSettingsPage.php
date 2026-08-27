<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationGroup = 'Sitio Web';
    protected static ?string $navigationLabel = 'Datos de Contacto';
    protected static ?string $title = 'Datos de Contacto';
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.site-settings-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Horario y Ubicación')
                    ->description('Se muestran en el pie de página del sitio.')
                    ->schema([
                        Forms\Components\Textarea::make('schedule')
                            ->label('Horario de Atención')
                            ->rows(2)
                            ->required(),
                        Forms\Components\Textarea::make('schedule_en')
                            ->label('Horario de Atención (Inglés)')
                            ->rows(2),
                        Forms\Components\Textarea::make('location')
                            ->label('Ubicación')
                            ->rows(2)
                            ->required(),
                        Forms\Components\Textarea::make('location_en')
                            ->label('Ubicación (Inglés)')
                            ->rows(2),
                    ])->columns(2),

                Forms\Components\Section::make('Canales de Atención')
                    ->schema([
                        Forms\Components\TextInput::make('phone_whatsapp')
                            ->label('Teléfono / WhatsApp')
                            ->helperText('Solo números, con código de país. Ej: 56944538170')
                            ->tel()
                            ->required(),
                        Forms\Components\TextInput::make('whatsapp_only')
                            ->label('WhatsApp')
                            ->helperText('Solo números, con código de país.')
                            ->tel(),
                        Forms\Components\TextInput::make('email')
                            ->label('Correo')
                            ->email()
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Ventas')
                    ->description('Contactos de venta que aparecen en el pie de página. Podés agregar, editar o quitar los que quieras.')
                    ->schema([
                        Forms\Components\Repeater::make('sales_contacts')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Título')
                                    ->placeholder('Ventas Nacional / Exportación')
                                    ->required(),
                                Forms\Components\TextInput::make('title_en')
                                    ->label('Título (Inglés)')
                                    ->placeholder('National Sales / Export'),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Teléfono')
                                    ->helperText('Solo números, con código de país.')
                                    ->tel(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Correo')
                                    ->email(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Agregar contacto de ventas')
                            ->reorderable(true)
                            ->collapsible(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        SiteSetting::current()->update($this->form->getState());

        Notification::make()
            ->title('Datos de contacto guardados')
            ->success()
            ->send();
    }
}
