<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/detalleventaModel.php';
require_once 'models/ventaModel.php';
require_once 'models/productoModel.php';

class DetalleVentaController{ 

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function guardar($venta_id, $detalles = [])
    {
        $response = [];
        //var_dump($detalles); die();
        if(count($detalles) > 0){
            foreach($detalles as $item){

                $nuevo = new DetalleVenta;
                $nuevo->venta_id = intval($venta_id);
                $nuevo->cantidad = intval($item->cantidad);
                $nuevo->precio_venta = floatval($item->precio_venta);
                $nuevo->total = floatval($item->total);
                $nuevo->producto_id = intval($item->producto_id);
                $nuevo->es_orden = $item->es_orden;

                $nuevo->save();

                $stop = $nuevo->cantidad * (-1);
                $this->actualizar_producto($item->producto_id, $stop, $item->precio_venta);

            }


        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No ahi productos para guardar',
                'detalle_ventas' => null,
            ];

        }
         return $response;

    }

    protected function actualizar_producto($id_producto, $stock){
        $producto  = Producto::find($id_producto);
        $producto->stock += $stock;
        $producto->save();
    }
}    