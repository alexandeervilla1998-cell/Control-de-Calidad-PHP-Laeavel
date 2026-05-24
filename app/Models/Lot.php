<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Lot extends Model
{
    protected $table = 'lot';
    protected $primaryKey = 'lot_id';
    public $timestamps = false;
    protected $fillable = [
        'created', 
        'createdby', 
        'updated', 
        'updatedby', 
        'isactive', 
        'name', 
        'production_date', 
        'analysis_date', 
        'product_id'
    ];

    protected $casts = [
        'created' => 'datetime',
        'updated' => 'datetime',
        'production_date' => 'datetime',
        'analysis_date' => 'datetime'
    ];

    /**
     * Relacion 1:n inversa: Devuelve el producto al que pertenece el lote
     *
     * @return BelongsTo - registro de producto
     */
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class,'product_id', 'product_id');
    }

    /**
     * Relacion 1:1 directa: Un lote solo puede tener un analisis de lote 
     *
     * @return HasOne - lote analizado
     */
    function batchAnalysis() : HasOne {
        return $this->hasOne(BatchAnalysis::class,'lot_id', 'lot_id');
    }

    // uso de Query Scope: es una característica avanzada de Eloquent que
    // permite definir un conjunto de restricciones en las consultas, 
    // lo que hace que el código sea más eficiente y más fácil de leer
    public function scopeWithQualityStats($query)
    {
        return $query->join('batch_analysis', 'lot.lot_id', '=', 'batch_analysis.lot_id')
        ->join('batch_analysis_line', 'batch_analysis.batch_analysis_id', '=', 'batch_analysis_line.batch_analysis_id')
        ->join('product', 'lot.product_id', '=', 'product.product_id')
        ->join('quality_parameters', function($join) {
            $join->on('product.product_id', '=', 'quality_parameters.product_id')
                 ->where('quality_parameters.isactive', '=', 'Y');
        })
        ->select(
            'lot.*',
            // Promedios calculados
            DB::raw('AVG(batch_analysis_line.moisture) as avg_moisture'),
            DB::raw('AVG(batch_analysis_line.temperature) as avg_temperature'),
            DB::raw('AVG(batch_analysis_line.sodium) as avg_sodium'),
            DB::raw('AVG(batch_analysis_line.protein) as avg_protein'),
            // Límites de cada caracteristica
            'quality_parameters.min_moisture as min_moisture',
            'quality_parameters.max_moisture as max_moisture',
            'quality_parameters.min_temperature as min_temperature',
            'quality_parameters.max_temperature as max_temperature',
            'quality_parameters.min_sodium as min_sodium',
            'quality_parameters.max_sodium as max_sodium',
            'quality_parameters.min_protein as min_protein',
            'quality_parameters.max_protein as max_protein'
        )->groupBy(
            'lot.lot_id',
            'quality_parameters.min_moisture',
            'quality_parameters.max_moisture',
            'quality_parameters.min_temperature',
            'quality_parameters.max_temperature',
            'quality_parameters.min_sodium',
            'quality_parameters.max_sodium',
            'quality_parameters.min_protein',
            'quality_parameters.max_protein'
        );
    }
}
