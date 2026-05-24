<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\QualityParameters;
use Exception;
use Illuminate\Http\Request;

class QualityParametersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $qparameters = QualityParameters::with(['product.productionLine'])->get();

            return view('quality_parameters.index', compact('qparameters'));
        } catch (Exception $th) {
            return redirect()->back()->with('error', 'Error grave al acceder a la vista.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $products = Product::all();
            return view('quality_parameters.create', compact('products'));
        } catch (Exception $th) {
            return redirect()->back()->with('error', 'Error grave al acceder a la vista.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // validamos los datos recibidos del formulario
            $validatedData = $request->validate([
                'isactive' => 'required|string|max:1',
                'product_id' => 'required|integer|exists:product,product_id',
                'min_moisture' => 'required|numeric',
                'max_moisture' => 'required|numeric',
                'min_temperature' => 'required|numeric',
                'max_temperature' => 'required|numeric',
                'min_sodium' => 'required|numeric',
                'max_sodium' => 'required|numeric',
                'min_protein' => 'required|numeric',
                'max_protein' => 'required|numeric',
            ]);

            // seteamos los datos para crear un nuevo producto
            $data = new QualityParameters();
            $data->created = now();
            $data->createdby = 0;
            $data->updated = now();
            $data->updatedby = 0;
            $data->isactive = $validatedData['isactive'];
            $data->min_moisture = $validatedData['min_moisture'];
            $data->max_moisture = $validatedData['max_moisture'];
            $data->min_temperature = $validatedData['min_temperature'];
            $data->max_temperature = $validatedData['max_temperature'];
            $data->min_sodium = $validatedData['min_sodium'];
            $data->max_sodium = $validatedData['max_sodium'];
            $data->min_protein = $validatedData['min_protein'];
            $data->max_protein = $validatedData['max_protein'];
            $data->product_id = $validatedData['product_id'];

            // creamos un nuevo registro en la base de datos
            $isOK = $data->save();

            if ($isOK) {
                // redirigimos con un mensaje de éxito
                return redirect()->route('quality_parameters.index')->with('success', 'Registro creado exitosamente.');
            } else {
                return redirect()->back()
                ->withInput() // Envía los datos del formulario de vuelta
                ->with('error', 'Error al crear el registro.');
            }
        } catch (Exception $ex) {
            return redirect()->back()
            ->withInput()
            ->with('error', 'Error grave al crear el registro.'. $ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            // buscamos el registro por su id
            $qparameter = QualityParameters::find($id);

            // obtenemos el listado de productos
            $products = Product::all();

            if ($qparameter == null || $products == null) {
                // si no se encuentra el registro, redirigimos con un mensaje de error
                return redirect()->route('quality_parameters.index')->with('error', 'Registro no encontrado.');
            }

            // retornamos la vista con los datos del registro encontrado
            return view('quality_parameters.edit', compact('qparameter', 'products'));            
        } catch (Exception $ex) {
            return redirect()->back()
            ->withInput()
            ->with('error', 'Error grave al buscar el registro.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // validamos los datos recibidos del formulario
            $validatedData = $request->validate([
                'isactive' => 'required|string|max:1',
                'product_id' => 'required|integer|exists:product,product_id',
                'min_moisture' => 'required|numeric',
                'max_moisture' => 'required|numeric',
                'min_temperature' => 'required|numeric',
                'max_temperature' => 'required|numeric',
                'min_sodium' => 'required|numeric',
                'max_sodium' => 'required|numeric',
                'min_protein' => 'required|numeric',
                'max_protein' => 'required|numeric',
            ]);

            // seteamos los datos para crear un nuevo producto
            $data = QualityParameters::find($id);
            if ($data === null) {
                return redirect()->route('quality_parameters.index')->with('error', 'Registro no encontrado.');
            }
            $data->updated = now();
            $data->updatedby = 0;
            $data->isactive = $validatedData['isactive'];
            $data->min_moisture = $validatedData['min_moisture'];
            $data->max_moisture = $validatedData['max_moisture'];
            $data->min_temperature = $validatedData['min_temperature'];
            $data->max_temperature = $validatedData['max_temperature'];
            $data->min_sodium = $validatedData['min_sodium'];
            $data->max_sodium = $validatedData['max_sodium'];
            $data->min_protein = $validatedData['min_protein'];
            $data->max_protein = $validatedData['max_protein'];
            $data->product_id = $validatedData['product_id'];

            // creamos un nuevo registro en la base de datos
            $isOK = $data->save();

            if ($isOK) {
                // redirigimos con un mensaje de éxito
                return redirect()->route('quality_parameters.index')->with('success', 'Registro actualizado exitosamente.');
            } else {
                return redirect()->back()
                ->withInput() // Envía los datos del formulario de vuelta
                ->with('error', 'Error al actualizar el registro.');
            }
        } catch (Exception $ex) {
            return redirect()->back()
            ->withInput()
            ->with('error', 'Error grave al actualizar el registro.'. $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // buscamos el registro por su id
            $qparameter = QualityParameters::find($id);

            if ($qparameter == null) {
                // si no se encuentra el registro, redirigimos con un mensaje de error
                return redirect()->route('quality_parameters.index')->with('error', 'Registro no encontrado.');
            }

            // eliminamos el registro de la base de datos
            $isOK = $qparameter->delete();

            if ($isOK) {
                // redirigimos al usuario con un mensaje de éxito
                return redirect()->route('quality_parameters.index')->with('success', 'Registro eliminado exitosamente.');
            } else {
                return redirect()->back()
                ->with('error', 'Error al eliminar el registro.');
            }
        } catch (Exception $ex) {
            return redirect()->back()
            ->with('error', 'Error grave al eliminar el registro.');
        }
    }
}
