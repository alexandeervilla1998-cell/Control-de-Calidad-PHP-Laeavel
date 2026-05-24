<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:product,product_id',
            'name' => 'required|string|max:100',
            'production_date' => 'required|date',
            'isactive' => 'required|in:Y,N',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Debe seleccionar un producto.',
            'product_id.exists' => 'El producto seleccionado no existe.',
            'name.required' => 'El nombre del lote es requerido.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'production_date.required' => 'La fecha de producción es requerida.',
            'production_date.date' => 'La fecha de producción debe ser una fecha válida.',
            'isactive.required' => 'Debe especificar si el lote está activo.',
            'isactive.in' => 'El valor de estado debe ser Y o N.',
        ];
    }
}
