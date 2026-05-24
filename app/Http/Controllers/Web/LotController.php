<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLotRequest;
use App\Http\Requests\UpdateLotRequest;
use App\Models\Product;
use App\Services\LotService;
use Exception;

class LotController extends Controller
{
    protected $lotService;

    public function __construct(LotService $lotService)
    {
        $this->lotService = $lotService;
    }

    /**
     * Mostrar listado de lotes.
     */
    public function index()
    {
        try {
            $lots = $this->lotService->getAllPaginated(15);
            return view('lot.index', compact('lots'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        try {
            $products = Product::all();
            return view('lot.create', compact('products'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Guardar nuevo lote.
     */
    public function store(StoreLotRequest $request)
    {
        try {
            $this->lotService->create($request->validated());
            return redirect()->route('lot.index')->with('success', 'Lote creado exitosamente.');
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
            $lot = $this->lotService->getById($id);
            $products = Product::all();
            return view('lot.edit', compact('lot', 'products'));
        } catch (Exception $e) {
            return redirect()->route('lot.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Actualizar lote.
     */
    public function update(UpdateLotRequest $request, string $id)
    {
        try {
            $this->lotService->update($id, $request->validated());
            return redirect()->route('lot.index')->with('success', 'Lote actualizado exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Eliminar lote.
     */
    public function destroy(string $id)
    {
        try {
            $this->lotService->delete($id);
            return redirect()->route('lot.index')->with('success', 'Lote eliminado exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

