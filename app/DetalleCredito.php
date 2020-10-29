<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCredito extends Model
{
    protected $table = 'detalle_creditos';
    protected $fillable = [
        'idcredito', 
        'idproducto',
        'cantidad',
        'precio',
        'descuento'
    ];
    
    public $timestamps = false;
}
