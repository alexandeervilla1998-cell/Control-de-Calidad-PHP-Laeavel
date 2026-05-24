<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\BatchAnalysisLine;
use Symfony\Component\HttpFoundation\Request;

class BatchAnalysisLineController extends Controller {
    
    public function show(Request $request)
    {
        try {
            // aqui usamos with para Eager Loading
            $lines = BatchAnalysisLine::with([
                'batchsAnalysis.lot.product.activeQualityParameters'
            ])->where('isactive', 'Y')->get();
            return response()->json($lines);
        } catch (\Exception $th) {
            $message = ["message" => "Error grave al cargar los datos" . $th->getMessage(), "status" => false];
            return response()->json($message,500);
        }
    }
}