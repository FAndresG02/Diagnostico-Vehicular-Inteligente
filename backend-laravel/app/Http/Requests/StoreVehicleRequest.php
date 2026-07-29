<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'anio' => 'required|string|max:10',
            'vin' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'marca.required' => 'El campo marca es obligatorio.',
            'modelo.required' => 'El campo modelo es obligatorio.',
            'anio.required' => 'El campo anio es obligatorio.',
            'vin.required' => 'El campo VIN es obligatorio.',
        ];
    }
}
