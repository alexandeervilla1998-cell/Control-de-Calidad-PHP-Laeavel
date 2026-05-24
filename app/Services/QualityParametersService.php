<?php

namespace App\Services;

use App\Models\QualityParameters;
use Exception;

class QualityParametersService
{
    /**
     * Obtener todos los parámetros de calidad con paginación
     */
    public function getAllPaginated($perPage = 15)
    {
        return QualityParameters::with(['product.productionLine'])
            ->paginate($perPage);
    }

    /**
     * Obtener todos los parámetros de calidad sin paginación
     */
    public function getAll()
    {
        return QualityParameters::with(['product.productionLine'])->get();
    }

    /**
     * Obtener un parámetro de calidad por ID
     */
    public function getById($id)
    {
        $parameter = QualityParameters::with(['product.productionLine'])->find($id);
        
        if (!$parameter) {
            throw new Exception('Parámetro de calidad no encontrado.');
        }
        
        return $parameter;
    }

    /**
     * Obtener parámetros activos por producto
     */
    public function getActiveByProductId($productId)
    {
        return QualityParameters::where('product_id', $productId)
            ->where('isactive', 'Y')
            ->first();
    }

    /**
     * Crear parámetros de calidad
     */
    public function create(array $data)
    {
        try {
            $parameter = new QualityParameters();
            $parameter->product_id = $data['product_id'];
            $parameter->min_moisture = $data['min_moisture'];
            $parameter->max_moisture = $data['max_moisture'];
            $parameter->min_temperature = $data['min_temperature'];
            $parameter->max_temperature = $data['max_temperature'];
            $parameter->min_sodium = $data['min_sodium'];
            $parameter->max_sodium = $data['max_sodium'];
            $parameter->min_protein = $data['min_protein'];
            $parameter->max_protein = $data['max_protein'];
            $parameter->isactive = $data['isactive'];
            $parameter->created = now();
            $parameter->createdby = auth()->id() ?? 0;
            $parameter->updated = now();
            $parameter->updatedby = auth()->id() ?? 0;
            
            $parameter->save();
            
            return $parameter;
        } catch (Exception $e) {
            throw new Exception('Error al crear los parámetros de calidad: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar parámetros de calidad
     */
    public function update($id, array $data)
    {
        try {
            $parameter = $this->getById($id);
            
            $parameter->product_id = $data['product_id'];
            $parameter->min_moisture = $data['min_moisture'];
            $parameter->max_moisture = $data['max_moisture'];
            $parameter->min_temperature = $data['min_temperature'];
            $parameter->max_temperature = $data['max_temperature'];
            $parameter->min_sodium = $data['min_sodium'];
            $parameter->max_sodium = $data['max_sodium'];
            $parameter->min_protein = $data['min_protein'];
            $parameter->max_protein = $data['max_protein'];
            $parameter->isactive = $data['isactive'];
            $parameter->updated = now();
            $parameter->updatedby = auth()->id() ?? 0;
            
            $parameter->save();
            
            return $parameter;
        } catch (Exception $e) {
            throw new Exception('Error al actualizar los parámetros de calidad: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar parámetros de calidad
     */
    public function delete($id)
    {
        try {
            $parameter = $this->getById($id);
            $parameter->delete();
            
            return true;
        } catch (Exception $e) {
            throw new Exception('Error al eliminar los parámetros de calidad: ' . $e->getMessage());
        }
    }
}
