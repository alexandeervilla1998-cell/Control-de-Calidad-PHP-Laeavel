<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQualityParametersRequest extends FormRequest
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
            'min_moisture' => 'required|numeric|min:0',
            'max_moisture' => 'required|numeric|min:0|gt:min_moisture',
            'min_temperature' => 'required|numeric|min:0',
            'max_temperature' => 'required|numeric|min:0|gt:min_temperature',
            'min_sodium' => 'required|numeric|min:0',
            'max_sodium' => 'required|numeric|min:0|gt:min_sodium',
            'min_protein' => 'required|numeric|min:0',
            'max_protein' => 'required|numeric|min:0|gt:min_protein',
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
            'min_moisture.required' => 'La humedad mínima es requerida.',
            'min_moisture.numeric' => 'La humedad mínima debe ser un número.',
            'max_moisture.required' => 'La humedad máxima es requerida.',
            'max_moisture.gt' => 'La humedad máxima debe ser mayor que la mínima.',
            'min_temperature.required' => 'La temperatura mínima es requerida.',
            'min_temperature.numeric' => 'La temperatura mínima debe ser un número.',
            'max_temperature.required' => 'La temperatura máxima es requerida.',
            'max_temperature.gt' => 'La temperatura máxima debe ser mayor que la mínima.',
            'min_sodium.required' => 'El sodio mínimo es requerido.',
            'min_sodium.numeric' => 'El sodio mínimo debe ser un número.',
            'max_sodium.required' => 'El sodio máximo es requerido.',
            'max_sodium.gt' => 'El sodio máximo debe ser mayor que el mínimo.',
            'min_protein.required' => 'La proteína mínima es requerida.',
            'min_protein.numeric' => 'La proteína mínima debe ser un número.',
            'max_protein.required' => 'La proteína máxima es requerida.',
            'max_protein.gt' => 'La proteína máxima debe ser mayor que la mínima.',
            'isactive.required' => 'Debe especificar si los parámetros están activos.',
            'isactive.in' => 'El valor de estado debe ser Y o N.',
        ];
    }
}
