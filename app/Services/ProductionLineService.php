<?php

namespace App\Services;

use App\Models\ProductionLine;
use Illuminate\Pagination\Paginator;
use Exception;

class ProductionLineService
{
    /**
     * Obtener todas las líneas de producción con paginación
     */
    public function getAllPaginated($perPage = 15)
    {
        return ProductionLine::with('products')
            ->paginate($perPage);
    }

    /**
     * Obtener todas las líneas de producción sin paginación
     */
    public function getAll()
    {
        return ProductionLine::with('products')->get();
    }

    /**
     * Obtener una línea de producción por ID
     */
    public function getById($id)
    {
        $productionLine = ProductionLine::with('products')->find($id);
        
        if (!$productionLine) {
            throw new Exception('Línea de producción no encontrada.');
        }
        
        return $productionLine;
    }

    /**
     * Crear una nueva línea de producción
     */
    public function create(array $data)
    {
        try {
            $productionLine = new ProductionLine();
            $productionLine->name = $data['name'];
            $productionLine->description = $data['description'];
            $productionLine->isactive = $data['isactive'];
            $productionLine->created = now();
            $productionLine->createdby = auth()->id() ?? 0;
            $productionLine->updated = now();
            $productionLine->updatedby = auth()->id() ?? 0;
            
            $productionLine->save();
            
            return $productionLine;
        } catch (Exception $e) {
            throw new Exception('Error al crear la línea de producción: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar una línea de producción
     */
    public function update($id, array $data)
    {
        try {
            $productionLine = $this->getById($id);
            
            $productionLine->name = $data['name'];
            $productionLine->description = $data['description'];
            $productionLine->isactive = $data['isactive'];
            $productionLine->updated = now();
            $productionLine->updatedby = auth()->id() ?? 0;
            
            $productionLine->save();
            
            return $productionLine;
        } catch (Exception $e) {
            throw new Exception('Error al actualizar la línea de producción: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una línea de producción
     */
    public function delete($id)
    {
        try {
            $productionLine = $this->getById($id);
            $productionLine->delete();
            
            return true;
        } catch (Exception $e) {
            throw new Exception('Error al eliminar la línea de producción: ' . $e->getMessage());
        }
    }
}
