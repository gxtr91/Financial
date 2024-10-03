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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();  // Campo ID auto incrementable
            $table->string('nombre_cuenta');  // Nombre de la cuenta
            $table->text('descripcion')->nullable();  // Descripción de la cuenta, puede ser nulo
            $table->decimal('presupuesto', 15, 2);  // Presupuesto de la cuenta con 2 decimales
            $table->timestamps();  // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};