<?php

namespace App\Services;

use App\Models\Product;
use Exception;

class ProductService
{
    /**
     * Obtener todos los productos con paginación
     */
    public function getAllPaginated($perPage = 15)
    {
        return Product::with('productionLine')
            ->paginate($perPage);
    }

    /**
     * Obtener todos los productos sin paginación
     */
    public function getAll()
    {
        return Product::with('productionLine')->get();
    }

    /**
     * Obtener un producto por ID
     */
    public function getById($id)
    {
        $product = Product::with(['productionLine', 'qualityParameters'])->find($id);
        
        if (!$product) {
            throw new Exception('Producto no encontrado.');
        }
        
        return $product;
    }

    /**
     * Crear un nuevo producto
     */
    public function create(array $data)
    {
        try {
            $product = new Product();
            $product->production_line_id = $data['production_line_id'];
            $product->name = $data['name'];
            $product->code = $data['code'];
            $product->picture = $data['picture'] ?? null;
            $product->isactive = $data['isactive'];
            $product->created = now();
            $product->createdby = auth()->id() ?? 0;
            $product->updated = now();
            $product->updatedby = auth()->id() ?? 0;
            
            $product->save();
            
            return $product;
        } catch (Exception $e) {
            throw new Exception('Error al crear el producto: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar un producto
     */
    public function update($id, array $data)
    {
        try {
            $product = $this->getById($id);
            
            $product->production_line_id = $data['production_line_id'];
            $product->name = $data['name'];
            $product->code = $data['code'];
            $product->picture = $data['picture'] ?? $product->picture;
            $product->isactive = $data['isactive'];
            $product->updated = now();
            $product->updatedby = auth()->id() ?? 0;
            
            $product->save();
            
            return $product;
        } catch (Exception $e) {
            throw new Exception('Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un producto
     */
    public function delete($id)
    {
        try {
            $product = $this->getById($id);
            $product->delete();
            
            return true;
        } catch (Exception $e) {
            throw new Exception('Error al eliminar el producto: ' . $e->getMessage());
        }
    }
}
