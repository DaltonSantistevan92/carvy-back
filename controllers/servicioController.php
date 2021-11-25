<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'core/params.php';
require_once 'models/ordenModel.php';
require_once 'models/servicioModel.php'; 


class ServicioController
{
    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }


    public  function guardar($orden, $venta_id){
        $orden->orden_id = intval($orden->orden_id);

        $objOrden = Orden::find($orden->orden_id);
        
        $nuevo = new Servicio;
        $nuevo->orden_id = $orden->orden_id;
        $nuevo->venta_id = intVal($venta_id);
        $nuevo->suma= $objOrden->suma;
        $nuevo->fecha = date('Y-m-d');

        //Actualizar la orden a facturado
        $objOrden->facturado = 'S';

        $objOrden->save();

        $nuevo->save();
        
    }

}