<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/usuarioModel.php';
require_once 'models/clienteModel.php';
require_once 'models/empresaModel.php';
require_once 'models/servicioModel.php';
require_once 'models/detalleventaModel.php';

use Illuminate\Database\Eloquent\Model;

class Venta extends Model{

    protected $table = "ventas";
    protected $filleable = ['serie', 'usuario_id', 'cliente_id', 'empresa_id', 'subtotal', 'iva', 'descuento_porcentaje', 'descuento_efectivo', 'total', 'fecha_venta', 'hora_venta', 'estado'];

    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }

    public function detalle_venta(){
        return $this->hasMany(DetalleVenta::class); 
    } 

    public function servicio(){
        return $this->hasMany(Servicio::class); 
    }


    
}