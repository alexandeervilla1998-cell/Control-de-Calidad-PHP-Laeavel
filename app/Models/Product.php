<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';
    public $timestamps = false;
    protected $fillable = [
        'created', 
        'createdby', 
        'updated', 
        'updatedby', 
        'isactive', 
        'name', 
        'code', 
        'picture', 
        'production_line_id'
    ];

    protected $casts = [
        'created' => 'datetime',
        'updated' => 'datetime',
    ];
    
    // indico que voy a obtener la linea de produccion
    // a la que pertece el producto
    public function productionLine(): BelongsTo {
        return $this->belongsTo(ProductionLine::class,'production_line_id', 'production_line_id');
    }

    /**
     * Relacion 1:n directa: Devuelve los parámetros de calidad que pertenecen al producto
     *
     * @return HasMany - listado de parámetros de calidad
     */
    public function qualityParameters(): HasMany {
        return $this->hasMany(QualityParameters::class,'product_id', 'product_id');
    }

    /**
     * Relacion 1:1 directa: Devuelve los parámetros de calidad que pertenece al producto
     *
     * @return HasOne - parámetros de calidad en el registro activo
     */
    public function activeQualityParameters(): HasOne {
        return $this->hasOne(QualityParameters::class,'product_id', 'product_id')->where('isactive', 'Y');
    }

    /**
     * Relacion 1:n directa: Devuelve los lotes que pertenecen al producto
     *
     * @return HasMany - listado de lotes
     */
    public function lots(): HasMany {
        return $this->hasMany(Lot::class,'product_id', 'product_id');
    }
}
