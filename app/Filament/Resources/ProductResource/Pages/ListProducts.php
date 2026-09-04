<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Services\FrontendRevalidator;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // El arrastrar-y-soltar de Filament actualiza sort_order con una
    // consulta directa a la base de datos (no pasa por ->save() de cada
    // producto), así que el aviso automático al frontend que sí se
    // dispara al editar un producto normal (ver ProductObserver) nunca
    // ocurre acá. Sin esto, el nuevo orden queda bien guardado en la base
    // de datos pero la tienda sigue mostrando el viejo hasta una hora
    // después. Se dispara a mano, justo después del reordenamiento real.
    public function reorderTable(array $order): void
    {
        parent::reorderTable($order);

        FrontendRevalidator::tag('products');
    }

    // Separadas en pestañas para que el orden (arrastrar y soltar) se
    // maneje de a un tipo por vez — vinos y packs son dos listas
    // independientes en la tienda, mezcladas en una sola tabla era
    // confuso para saber qué se estaba reordenando.
    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos'),
            'vinos' => Tab::make('Vinos')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_pack', false)),
            'packs' => Tab::make('Packs')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_pack', true)),
        ];
    }
}
