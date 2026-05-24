<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BatchAnalysis;
use App\Models\BatchAnalysisState;
use App\Models\Lot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $batchsAnalysis = BatchAnalysis::with([
                'lot.product.productionLine',
                'batchAnalysisState',
                'batchAnalysisLines',
            ])
                ->where('isactive', 'Y')
                ->whereHas('batchAnalysisState', function ($query) {
                    $query->where('name', 'en proceso');
                })->orderBy('created', 'ASC')->get();

            return view('batch_analysis.index', compact('batchsAnalysis'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Error grave al acceder a la vista.');
        }
    }

    public function historial(Request $request)
    {
        try {
            return view('batch_analysis.historial');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Error grave al acceder a la vista.');
        }
    }
    
    public function buscarHistorialPorFecha(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "date_front" => 'required|string'
            ]);

            $date_front = $validatedData["date_front"];

            // convertimos el string fecha a un objeto fecha
            $date = Carbon::parse($date_front);

            // buscamos los analisis que esten activos y por la fecha
            $batchsAnalysis = BatchAnalysis::where('isactive', 'Y')
                ->whereHas('batchAnalysisState', function ($query) use ($date) {
                    $query->where('name', 'finalizado')
                    ->where('isactive', 'Y')
                    ->whereDate('datefrom', $date);
                })->orderBy('created', 'ASC')->get();

            return view('batch_analysis.partialhistorial', compact('batchsAnalysis'));
        } catch (\Exception $th) {
            $message = ["message" => "Error grave al cargar los datos" . $th->getMessage(), "status" => false];
            return response()->json($message,500);
        }
    }

    public function update(string $id)
    {
        DB::beginTransaction();
        try {
            
            $batchAn = BatchAnalysis::find($id);
            if ($batchAn === null) {
                return redirect()->back()->with('error', 'Análisis de lote no encontrado.');
            }

            $batchAn->updated = now();
            $batchAn->updatedby = 0;
            $batchAn->save();

            $batchAnSt = BatchAnalysisState::where('batch_analysis_id', $batchAn->batch_analysis_id)->first();
            if ($batchAnSt === null) {
                DB::rollBack();

                return redirect()->back()->with('error', 'Estado del análisis no encontrado.');
            }

            $batchAnSt->updated = $batchAn->updated;
            $batchAnSt->updatedby = 0;
            $batchAnSt->name = 'finalizado';
            $batchAnSt->dateto = $batchAn->updated;
            $batchAnSt->datediif = $batchAnSt->dateto->diffInHours($batchAnSt->datefrom);
            $batchAnSt->save();
            
            $lot = Lot::find($batchAn->lot_id);
            $lot->updated = $batchAn->updated;
            $lot->updatedby = 0;
            $lot->analysis_date = $batchAn->updated;
            $lot->save();

            DB::commit();

            return redirect()->back()->with('success', 'Registro actualizado.');
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error grave generado.' . $th->getMessage());
        }
    }

    
}
