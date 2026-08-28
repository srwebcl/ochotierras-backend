<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * "Clientes" no es una tabla propia: se arma agrupando `orders` por email,
 * porque el checkout es de invitado y nunca crea una cuenta de usuario.
 * Es una vista de solo lectura — nunca se inserta directo en `customers`.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS customers');

        DB::statement("
            CREATE VIEW customers AS
            SELECT
                customer_email AS email,
                MAX(customer_name) AS name,
                MAX(customer_phone) AS phone,
                MAX(customer_rut) AS rut,
                COUNT(*) AS orders_count,
                SUM(CASE WHEN status = 'PAID' THEN total_amount ELSE 0 END) AS total_spent,
                MIN(created_at) AS first_order_at,
                MAX(created_at) AS last_order_at
            FROM orders
            WHERE customer_email IS NOT NULL
            GROUP BY customer_email
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS customers');
    }
};
