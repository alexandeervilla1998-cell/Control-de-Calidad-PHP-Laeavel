<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQualityParametersRequest;
use App\Http\Requests\UpdateQualityParametersRequest;
use App\Models\Product;
use App\Services\QualityParametersService;
use Exception;

class QualityParametersController extends Controller
{
    protected $qualityParametersService;

    public function __construct(QualityParametersService $qualityParametersService)
    {
        $this->qualityParametersService = $qualityParametersService;
    }

    /**
     * Mostrar listado de parámetros de calidad.
     */
    public function index()
    {
        try {
            $qparameters = $this->qualityParametersService->getAllPaginated(15);
            return view('quality_parameters.index', compact('qparameters'));
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
            return view('quality_parameters.create', compact('products'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Guardar nuevos parámetros de calidad.
     */
    public function store(StoreQualityParametersRequest $request)
    {
        try {
            $this->qualityParametersService->create($request->validated());
            return redirect()->route('quality_parameters.index')->with('success', 'Parámetros de calidad creados exitosamente.');
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
            $parameter = $this->qualityParametersService->getById($id);
            $products = Product::all();
            return view('quality_parameters.edit', compact('parameter', 'products'));
        } catch (Exception $e) {
            return redirect()->route('quality_parameters.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Actualizar parámetros de calidad.
     */
    public function update(UpdateQualityParametersRequest $request, string $id)
    {
        try {
            $this->qualityParametersService->update($id, $request->validated());
            return redirect()->route('quality_parameters.index')->with('success', 'Parámetros de calidad actualizados exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Eliminar parámetros de calidad.
     */
    public function destroy(string $id)
    {
        try {
            $this->qualityParametersService->delete($id);
            return redirect()->route('quality_parameters.index')->with('success', 'Parámetros de calidad eliminados exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
