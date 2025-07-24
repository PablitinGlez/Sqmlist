<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Illuminate\Support\Facades\Log;

class StepperInput extends Field
{
    protected string $view = 'forms.components.stepper-input';

    protected int $minValue = 0;
    protected ?int $maxValue = null;
    protected int $step = 1;

    public function minValue(int $value): static
    {
        $this->minValue = $value;
        return $this;
    }

    public function maxValue(?int $value): static
    {
        $this->maxValue = $value;
        return $this;
    }

    public function step(int $value): static
    {
        $this->step = $value;
        return $this;
    }

    public function getMinValue(): int
    {
        return $this->minValue;
    }

    public function getMaxValue(): ?int
    {
        return $this->maxValue;
    }

    public function getStep(): int
    {
        return $this->step;
    }

    protected function setUp(): void
    {
        parent::setUp();

        Log::info('StepperInput: setUp() para el campo ' . $this->getName());
        Log::info('StepperInput: minValue: ' . $this->getMinValue() . ', maxValue: ' . ($this->getMaxValue() ?? 'null') . ', step: ' . $this->getStep());

        $this->default(function (Field $component) {
            $defaultValue = $component->isNullable() ? null : $this->getMinValue();
            Log::info('StepperInput: default() para ' . $component->getName() . ', valor por defecto: ' . ($defaultValue ?? 'null'));
            return $defaultValue;
        });

        $this->formatStateUsing(function (mixed $state): mixed {
            $min = $this->getMinValue();
            $max = $this->getMaxValue();
            $fieldName = $this->getName();

            Log::info('StepperInput: formatStateUsing() para ' . $fieldName . ', estado inicial: ' . ($state === null ? 'null' : (is_scalar($state) ? $state : gettype($state))));

            if ($state === null || $state === '') {
                Log::info('StepperInput: ' . $fieldName . ' - Estado nulo/vacío, estableciendo a minValue: ' . $min);
                return $min;
            }

            $state = (int) $state;

            if ($state < $min) {
                Log::info('StepperInput: ' . $fieldName . ' - Estado menor que min, estableciendo a minValue: ' . $min);
                return $min;
            }

            if ($max !== null && $state > $max) {
                Log::info('StepperInput: ' . $fieldName . ' - Estado mayor que max, estableciendo a maxValue: ' . $max);
                return $max;
            }

            Log::info('StepperInput: ' . $fieldName . ' - Estado final formatStateUsing: ' . $state);
            return $state;
        });

        $this->dehydrateStateUsing(function (mixed $state): mixed {
            $fieldName = $this->getName();
            Log::info('StepperInput: dehydrateStateUsing() para ' . $fieldName . ', estado inicial: ' . ($state === null ? 'null' : (is_scalar($state) ? $state : gettype($state))));

            if ($state === '' || $state === null) {
                Log::info('StepperInput: ' . $fieldName . ' - Estado vacío/nulo al deshidratar, retornando null.');
                return null;
            }
            $finalState = (int) $state;
            Log::info('StepperInput: ' . $fieldName . ' - Estado final dehydrateStateUsing: ' . $finalState);
            return $finalState;
        });

        $this->rules([
            'numeric',
            'min:' . $this->minValue,
        ]);
        if ($this->maxValue !== null) {
            $this->rules(['max:' . $this->maxValue]);
        }
    }
}
