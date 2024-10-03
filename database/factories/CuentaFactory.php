<?php

namespace Database\Factories;

use App\Models\Cuenta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cuenta>
 */
class CuentaFactory extends Factory
{
    protected $model = Cuenta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre_cuenta' => $this->faker->company,  // Nombre aleatorio
            'descripcion' => $this->faker->sentence,  // Descripción aleatoria
            'presupuesto' => $this->faker->randomFloat(2, 1000, 10000),  // Presupuesto aleatorio entre 1000 y 10000
        ];
    }
}