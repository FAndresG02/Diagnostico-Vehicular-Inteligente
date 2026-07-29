<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDTCRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('El :attribute debe ser un texto.');
            return;
        }

        $cleaned = strtoupper(preg_replace('/[^A-Z0-9]/', '', $value));

        if (!preg_match('/^[PCBU][0-9]{4,5}$/', $cleaned)) {
            $fail('El :attribute no es un codigo DTC valido.');
        }
    }
}
