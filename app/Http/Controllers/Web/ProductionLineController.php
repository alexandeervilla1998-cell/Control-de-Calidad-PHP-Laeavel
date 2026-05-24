<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductionLineRequest;
use App\Http\Requests\UpdateProductionLineRequest;
use App\Services\ProductionLineService;
use Exception;

class ProductionLineController extends Controller
{
    protected $productionLineService;

    public function __construct(ProductionLineService $productionLineService)
    {
        $this->productionLineService = $productionLineService;
    }

    /**
     * Mostrar listado de líneas de producción.
     */
    public function index()
    {
        try {
            $productionLines = $this->productionLineService->getAllPaginated(15);
            return view('production_line.index', compact('productionLines'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('production_line.create');
    }

    /**
     * Guardar nueva línea de producción.
     */
    public function store(StoreProductionLineRequest $request)
    {
        try {
            $this->productionLineService->create($request->validated());
            return redirect()->route('production_line.index')->with('success', 'Línea de producción creada exitosamente.');
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
            $productionLine = $this->productionLineService->getById($id);
            return view('production_line.edit', compact('productionLine'));
        } catch (Exception $e) {
            return redirect()->route('production_line.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Actualizar línea de producción.
     */
    public function update(UpdateProductionLineRequest $request, string $id)
    {
        try {
            $this->productionLineService->update($id, $request->validated());
            return redirect()->route('production_line.index')->with('success', 'Línea de producción actualizada exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Eliminar línea de producción.
     */
    public function destroy(string $id)
    {
        try {
            $this->productionLineService->delete($id);
            return redirect()->route('production_line.index')->with('success', 'Línea de producción eliminada exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
}
