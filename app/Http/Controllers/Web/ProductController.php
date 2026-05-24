<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductionLine;
use App\Services\ProductService;
use Exception;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Mostrar listado de productos.
     */
    public function index()
    {
        try {
            $products = $this->productService->getAllPaginated(15);
            return view('product.index', compact('products'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $productionLines = ProductionLine::all();
        return view('product.create', compact('productionLines'));
    }

    /**
     * Guardar nuevo producto.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $this->productService->create($request->validated());
            return redirect()->route('product.index')->with('success', 'Producto creado exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(string $id)
    {
        try {
            $product = $this->productService->getById($id);
            $productionLines = ProductionLine::all();
            return view('product.edit', compact('product', 'productionLines'));
        } catch (Exception $e) {
            return redirect()->route('product.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Actualizar producto.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $this->productService->update($id, $request->validated());
            return redirect()->route('product.index')->with('success', 'Producto actualizado exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Eliminar producto.
     */
    public function destroy(string $id)
    {
        try {
            $this->productService->delete($id);
            return redirect()->route('product.index')->with('success', 'Producto eliminado exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
