<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BatchAnalysisLine;
use Illuminate\Http\Request;

class BatchAnalysisLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $batch_analysis_id = $request->batch_analysis_id;
            return view('batch_analysis_line.index', compact('batch_analysis_id'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Error grave al acceder a la vista.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'moisture' => 'required|numeric',
                'temperature' => 'required|numeric',
                'sodium' => 'required|numeric',
                'protein' => 'required|numeric',
                'number_batch' => 'required|numeric',
                'batch_analysis_id' => 'required|numeric'
            ]);

            $data = new BatchAnalysisLine();
            $data->created = now();
            $data->createdby = 0;
            $data->updated = now();
            $data->updatedby = 0;
            $data->isactive = 'Y';
            $data->moisture = $validatedData['moisture'];
            $data->temperature = $validatedData['temperature'];
            $data->sodium = $validatedData['sodium'];
            $data->protein = $validatedData['protein'];
            $data->number_batch = $validatedData['number_batch'];
            $data->batch_analysis_id = $validatedData['batch_analysis_id'];
            $isOK = $data->save();

            if ($isOK) {
                $message = ["message" => "Acción realizada correctamente", "status" => true];
                return response()->json($message);
            } else {
                $message = ["message" => "Error al guardar", "status" => false];
                return response()->json($message);
            }
        } catch (\Exception $th) {
            $message = ["message" => "Error grave al realizar la acción", "status" => false];
            return response()->json($message,500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
            $batch_analysis_id = $request->batch_analysis_id;
            // aqui usamos with para Eager Loading
            $lines = BatchAnalysisLine::with([
                'batchsAnalysis.lot.product.activeQualityParameters'
            ])->where('isactive', 'Y')
            ->where('batch_analysis_id', $batch_analysis_id)
            ->get();

            return view('batch_analysis_line.partialanalysis', compact('lines', 'batch_analysis_id'));
        } catch (\Exception $th) {
            $message = ["message" => "Error grave al cargar los datos" . $th->getMessage(), "status" => false];
            return response()->json($message,500);
        }
    }
    
    public function detail(Request $request)
    {
        try {
            $batch_analysis_id = $request->batch_analysis_id;
            return view('batch_analysis_line.detail', compact('batch_analysis_id'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Error grave al acceder a la vista.');
        }
    }
}
