<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Autoriza la solicitud.
     * Solo permite la creación si el usuario está autenticado.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Reglas de validación para crear una orden.
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],

            // Validación por cada ítem
            'items.*.sku'        => ['required', 'string', 'max:64'],
            'items.*.name'       => ['required', 'string', 'max:255'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],

            // Campo opcional para el impuesto (ej. 0.19)
            'tax_rate' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Mensajes de error personalizados (opcional).
     */
    public function messages(): array
    {
        return [
            'items.required' => 'Debes agregar al menos un producto a la orden.',
            'items.array'    => 'El campo items debe ser un arreglo válido.',
            'items.min'      => 'La orden debe contener al menos un ítem.',
            'items.*.sku.required' => 'Cada ítem debe tener un código SKU.',
            'items.*.quantity.min' => 'La cantidad mínima es 1 unidad.',
        ];
    }
}
