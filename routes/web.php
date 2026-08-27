<?php

use Illuminate\Support\Facades\Route;

// Este backend es una API + panel de administración (Filament). No hay
// sitio público que mostrar en la raíz, así que se entra directo al login.
Route::get('/', function () {
    return redirect('/admin');
});
