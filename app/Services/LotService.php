<?php

namespace App\Services;

use App\Models\Lot;
use Exception;

class LotService
{
    /**
     * Obtener todos los lotes con paginación
     */
    public function getAllPaginated($perPage = 15)
    {
        return Lot::with('product')
            ->paginate($perPage);
    }

    /**
     * Obtener todos los lotes sin paginación
     */
    public function getAll()
    {
        return Lot::with('product')->get();
    }

    /**
     * Obtener un lote por ID
     */
    public function getById($id)
    {
        $lot = Lot::with(['product', 'batchAnalysis'])->find($id);
        
        if (!$lot) {
            throw new Exception('Lote no encontrado.');
        }
        
        return $lot;
    }

    /**
     * Obtener lotes con estadísticas de calidad
     */
    public function getWithQualityStats()
    {
        return Lot::withQualityStats()->get();
    }

    /**
     * Crear un nuevo lote
     */
    public function create(array $data)
    {
        try {
            $lot = new Lot();
            $lot->product_id = $data['product_id'];
            $lot->name = $data['name'];
            $lot->production_date = $data['production_date'];
            $lot->isactive = $data['isactive'];
            $lot->created = now();
            $lot->createdby = auth()->id() ?? 0;
            $lot->updated = now();
            $lot->updatedby = auth()->id() ?? 0;
            
            $lot->save();
            
            return $lot;
        } catch (Exception $e) {
            throw new Exception('Error al crear el lote: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar un lote
     */
    public function update($id, array $data)
    {
        try {
            $lot = $this->getById($id);
            
            $lot->product_id = $data['product_id'];
            $lot->name = $data['name'];
            $lot->production_date = $data['production_date'];
            $lot->isactive = $data['isactive'];
            $lot->updated = now();
            $lot->updatedby = auth()->id() ?? 0;
            
            $lot->save();
            
            return $lot;
        } catch (Exception $e) {
            throw new Exception('Error al actualizar el lote: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un lote
     */
    public function delete($id)
    {
        try {
            $lot = $this->getById($id);
            $lot->delete();
            
            return true;
        } catch (Exception $e) {
            throw new Exception('Error al eliminar el lote: ' . $e->getMessage());
        }
    }
}
