<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchAnalysisLine extends Model
{
    protected $table = 'batch_analysis_line';
    protected $primaryKey = 'batch_analysis_line_id';
    public $timestamps = false;
    protected $fillable = [
        'created', 
        'createdby', 
        'updated', 
        'updatedby', 
        'isactive', 
        'moisture', 
        'temperature',
        'sodium', 
        'protein', 
        'number_batch', 
        'batch_analysis_id'
    ];

    protected $casts = [
        'created' => 'datetime',
        'updated' => 'datetime'
    ];

    function batchsAnalysis() : BelongsTo {
        return $this->belongsTo(BatchAnalysis::class, 'batch_analysis_id', 'batch_analysis_id');
    }

    // obtenemos los parametros de calidad
    public function getParametersAttribute() {
        return $this->batchsAnalysis->lot->product->activeQualityParameters;
    }
    
    public function getMoistureStateAttribute() {
        $limites = $this->parameters;
        
        if (!$limites) return 'sin_parametros';

        if ($this->moisture < $limites->min_moisture) {
            return 'bajo';
        }
        
        if ($this->moisture > $limites->max_moisture) {
            return 'alto';
        }

        return 'aceptable';
    }
    
    public function getTemperatureStateAttribute() {
        $limites = $this->parameters;
        
        if (!$limites) return 'sin_parametros';

        if ($this->temperature < $limites->min_temperature) {
            return 'bajo';
        }
        
        if ($this->temperature > $limites->max_temperature) {
            return 'alto';
        }

        return 'aceptable';
    }
    
    public function getSodiumStateAttribute() {
        $limites = $this->parameters;
        
        if (!$limites) return 'sin_parametros';

        if ($this->sodium < $limites->min_sodium) {
            return 'bajo';
        }
        
        if ($this->sodium > $limites->max_sodium) {
            return 'alto';
        }

        return 'aceptable';
    }
    
    public function getProteinStateAttribute() {
        $limites = $this->parameters;
        
        if (!$limites) return 'sin_parametros';

        if ($this->protein < $limites->min_protein) {
            return 'bajo';
        }
        
        if ($this->protein > $limites->max_protein) {
            return 'alto';
        }

        return 'aceptable';
    }
}
