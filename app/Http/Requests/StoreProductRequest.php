<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'production_line_id' => 'required|integer|exists:production_line,production_line_id',
            'name' => 'required|string|max:100|unique:product,name',
            'code' => 'required|string|max:100|unique:product,code',
            'picture' => 'nullable|string|max:500',
            'isactive' => 'required|in:Y,N',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'production_line_id.required' => 'Debe seleccionar una línea de producción.',
            'production_line_id.exists' => 'La línea de producción seleccionada no existe.',
            'name.required' => 'El nombre del producto es requerido.',
            'name.unique' => 'Ya existe un producto con este nombre.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'code.required' => 'El código del producto es requerido.',
            'code.unique' => 'Ya existe un producto con este código.',
            'code.max' => 'El código no puede exceder 100 caracteres.',
            'picture.max' => 'La ruta de la imagen no puede exceder 500 caracteres.',
            'isactive.required' => 'Debe especificar si el producto está activo.',
            'isactive.in' => 'El valor de estado debe ser Y o N.',
        ];
    }
}
