<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Antes, cualquier cuenta del panel podía ver, editar la contraseña y
 * eliminar a cualquier otra (incluida ella misma). Con esta columna, solo
 * los Super Admin pueden gestionar "Usuarios del Panel" — el resto sigue
 * pudiendo cambiar su propia contraseña desde su perfil (ver ->profile()
 * en AdminPanelProvider), pero no toca cuentas ajenas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->after('password');
        });

        // Las cuentas que ya existían quedan como Super Admin para que nadie
        // pierda acceso con este cambio — desde el panel se puede bajar el
        // nivel de las que correspondan.
        DB::table('users')->update(['is_super_admin' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_super_admin');
        });
    }
};
