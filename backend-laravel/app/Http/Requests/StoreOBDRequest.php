<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOBDRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dtc' => 'required|array|min:1',
            'dtc.*' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'dtc.required' => 'El campo dtc es obligatorio.',
            'dtc.array' => 'El campo dtc debe ser un arreglo.',
            'dtc.min' => 'Debe enviar al menos un codigo DTC.',
        ];
    }
}
