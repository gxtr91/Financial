<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cuenta'); // Clave foránea para cuentas
            $table->unsignedBigInteger('id_user');   // Clave foránea para users
            $table->decimal('monto', 10, 2);          // Monto de la transacción
            $table->string('descripcion');           // Descripción del pago realizado
            $table->timestamps();

            // Definir las claves foráneas
            $table->foreign('id_cuenta')->references('id')->on('cuentas')
                  ->onDelete('cascade');  // Asegura integridad referencial
            $table->foreign('id_user')->references('id')->on('users')
                  ->onDelete('cascade');  // Asegura integridad referencial
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};