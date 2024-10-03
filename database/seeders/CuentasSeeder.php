<?php

namespace Database\Seeders;

use App\Models\Cuenta;
use Illuminate\Database\Seeder;

class CuentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear 10 cuentas aleatorias
        Cuenta::factory()->count(10)->create();
    }
}