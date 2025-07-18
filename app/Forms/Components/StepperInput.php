<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Illuminate\Support\Facades\Log; // Importar la fachada Log

class StepperInput extends Field
{
    // Define la vista Blade para este componente personalizado.
    protected string $view = 'forms.components.stepper-input';

    // Propiedades para configurar el stepper
    protected int $minValue = 0;
    protected ?int $maxValue = null; // null significa sin límite superior
    protected int $step = 1;

    /**
     * Define el valor mínimo permitido para el campo.
     *
     * @param int $value El valor mínimo.
     * @return $this
     */
    public function minValue(int $value): static
    {
        $this->minValue = $value;
        return $this;
    }

    /**
     * Define el valor máximo permitido para el campo.
     *
     * @param int|null $value El valor máximo, o null para sin límite.
     * @return $this
     */
    public function maxValue(?int $value): static
    {
        $this->maxValue = $value;
        return $this;
    }

    /**
     * Define el incremento/decremento del paso.
     *
     * @param int $value El valor del paso.
     * @return $this
     */
    public function step(int $value): static
    {
        $this->step = $value;
        return $this;
    }

    /**
     * Obtiene el valor mínimo configurado.
     *
     * @return int
     */
    public function getMinValue(): int
    {
        return $this->minValue;
    }

    /**
     * Obtiene el valor máximo configurado.
     *
     * @return int|null
     */
    public function getMaxValue(): ?int
    {
        return $this->maxValue;
    }

    /**
     * Obtiene el valor del paso configurado.
     *
     * @return int
     */
    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * Configura el componente después de su inicialización.
     * Aquí definimos cómo se muta el estado al hidratar y deshidratar.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Log::info('StepperInput: setUp() para el campo ' . $this->getName());
        Log::info('StepperInput: minValue: ' . $this->getMinValue() . ', maxValue: ' . ($this->getMaxValue() ?? 'null') . ', step: ' . $this->getStep());

        // Establecer un valor por defecto si el campo es requerido y el estado es nulo.
        $this->default(function (Field $component) {
            $defaultValue = $component->isNullable() ? null : $this->getMinValue();
            Log::info('StepperInput: default() para ' . $component->getName() . ', valor por defecto: ' . ($defaultValue ?? 'null'));
            return $defaultValue;
        });

        // === CLAVE: Mutar el estado antes de mostrarlo y antes de la validación. ===
        // Esto asegura que el valor siempre sea numérico y dentro de los límites
        // para que la regla 'required' lo reconozca correctamente.
        $this->formatStateUsing(function (mixed $state): mixed {
            $min = $this->getMinValue();
            $max = $this->getMaxValue();
            $fieldName = $this->getName();

            Log::info('StepperInput: formatStateUsing() para ' . $fieldName . ', estado inicial: ' . ($state === null ? 'null' : (is_scalar($state) ? $state : gettype($state))));

            // Si el estado es nulo o una cadena vacía, lo inicializamos al valor mínimo.
            if ($state === null || $state === '') {
                Log::info('StepperInput: ' . $fieldName . ' - Estado nulo/vacío, estableciendo a minValue: ' . $min);
                return $min;
            }

            $state = (int) $state; // Convertir a entero

            // Asegurar que el valor no sea menor que el mínimo
            if ($state < $min) {
                Log::info('StepperInput: ' . $fieldName . ' - Estado menor que min, estableciendo a minValue: ' . $min);
                return $min;
            }

            // Asegurar que el valor no sea mayor que el máximo (si hay un máximo definido)
            if ($max !== null && $state > $max) {
                Log::info('StepperInput: ' . $fieldName . ' - Estado mayor que max, estableciendo a maxValue: ' . $max);
                return $max;
            }

            Log::info('StepperInput: ' . $fieldName . ' - Estado final formatStateUsing: ' . $state);
            return $state;
        });

        // === CLAVE: Mutar el estado al deshidratar (cuando se envía el formulario). ===
        // Esta es la forma correcta para transformar el valor antes de guardarlo en la base de datos.
        $this->dehydrateStateUsing(function (mixed $state): mixed {
            $fieldName = $this->getName();
            Log::info('StepperInput: dehydrateStateUsing() para ' . $fieldName . ', estado inicial: ' . ($state === null ? 'null' : (is_scalar($state) ? $state : gettype($state))));

            if ($state === '' || $state === null) {
                Log::info('StepperInput: ' . $fieldName . ' - Estado vacío/nulo al deshidratar, retornando null.');
                return null; // Si está vacío, lo guardamos como null en la DB
            }
            $finalState = (int) $state;
            Log::info('StepperInput: ' . $fieldName . ' - Estado final dehydrateStateUsing: ' . $finalState);
            return $finalState; // Aseguramos que sea un entero para la DB
        });

        // Añadir reglas de validación
        $this->rules([
            'numeric',
            'min:' . $this->minValue,
        ]);
        if ($this->maxValue !== null) {
            $this->rules(['max:' . $this->maxValue]);
        }
    }
}
