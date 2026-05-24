<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductionLineRequest extends FormRequest
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
            'name' => 'required|string|max:100|unique:production_line,name',
            'description' => 'required|string|max:100',
            'isactive' => 'required|in:Y,N',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la línea es requerido.',
            'name.unique' => 'Ya existe una línea con este nombre.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'description.required' => 'La descripción es requerida.',
            'description.max' => 'La descripción no puede exceder 100 caracteres.',
            'isactive.required' => 'Debe especificar si la línea está activa.',
            'isactive.in' => 'El valor de estado debe ser Y o N.',
        ];
    }
}
