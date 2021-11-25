<?php

require_once 'vendor/autoload.php';
require_once 'core/conexion.php';
require_once 'models/clienteModel.php';
require_once 'models/vehiculoModel.php';
require_once 'models/usuarioModel.php';
require_once 'models/mecanicoModel.php';
require_once 'models/estadoModel.php';
require_once 'models/materialModel.php';
require_once 'models/servicioModel.php';
require_once 'models/ordenaveriasfallasModel.php';
require_once 'models/actividadModel.php';


use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{

    protected $table = "orden_de_trabajos";
    protected $fillable = ['cliente_id', 'vehiculo_id', 'usuario_id', 'mecanico_id', 'fecha', 'hora', 'descripcion', 'suma', 'fecha_trabajo', 'hora_inicio', 'fecha_trabajo_salida', 'hora_salida', 'estado', 'estado_orden_id', 'codigo', 'observacion','facturado'];

    //uno a muchos
    public function actividad()
    {
        return $this->hasMany(Actividad::class);
    }
    
    
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

    //Muchos a uno --- uno a muchos(Inverso)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    //Muchos a uno --- uno a muchos(Inverso)
    public function mecanico()
    {
        return $this->belongsTo(Mecanico::class);
    }

    //Muchos a uno --- uno a muchos(Inverso)
    //estado_orden
    public function estado_orden()
    {
        return $this->belongsTo(Estado::class);
    }

    //uno a muchos
    public function ordentrabajo_averiasfallas()
    {
        return $this->hasMany(OrdenAveriasFallas::class,'orden_de_trabajo_id', 'id');
    }

    //uno a muchos
    public function material()
    {
        return $this->hasMany(Material::class);
    }

     //uno a muchos
     public function servicio()
     {
         return $this->hasMany(Servicio::class); 
     }



}
