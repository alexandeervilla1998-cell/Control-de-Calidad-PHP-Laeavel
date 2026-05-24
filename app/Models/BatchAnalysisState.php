<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchAnalysisState extends Model
{
    protected $table = 'batch_analysis_state';
    protected $primaryKey = 'batch_analysis_state_id';
    public $timestamps = false;
    protected $fillable = [
        'created', 
        'createdby', 
        'updated', 
        'updatedby', 
        'isactive', 
        'name', 
        'datefrom', 
        'dateto', 
        'datediif', 
        'batch_analysis_id'
    ];

    protected $casts = [
        'created' => 'datetime',
        'updated' => 'datetime',
        'datefrom' => 'datetime', 
        'dateto' => 'datetime'
    ];

    public function getDateDiffStrAttribute() {
        try {
            $diff = $this->datefrom->diff($this->dateto);
            return $diff->h . " hrs y " . $diff->i . " min";
        } catch (\Throwable $th) {
            return "0 hrs y 0 min";
        }
    }
}
