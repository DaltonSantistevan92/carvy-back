<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/marcaModel.php';

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{

    protected $table = "vehiculos";
    protected $fillable = ['marca_id', 'placa', 'modelo', 'color', 'kilometro', 'libre', 'estado'];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
