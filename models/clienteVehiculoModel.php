<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/clienteModel.php';

use Illuminate\Database\Eloquent\Model;

class ClienteVehiculo extends Model
{

    protected $table = "clientes_vehiculos";
    protected $fillable = ['vehiculo_id', 'cliente_id', 'estado'];

    //Muchos a uno --- uno a muchos(Inverso)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    //Muchos a uno --- uno a muchos(Inverso)
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
}
