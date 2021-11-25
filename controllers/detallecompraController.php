<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/detallecompraModel.php';
require_once 'models/compraModel.php';
require_once 'models/productoModel.php';

class DetalleCompraController{ 

    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function listar(){
        $this->cors->corsJson();
        $detalles = DetalleCompra::where('compra_id', '8')->get();

        echo json_encode($detalles);
    }

    public function guardar($compra_id, $detalles = []){
      
        $response = [];
        if(count($detalles) > 0){

            foreach($detalles as $item){
                $nuevo = new DetalleCompra;

                $nuevo->compra_id = intval($compra_id);
                $nuevo->producto_id = intval($item->producto_id);
                $nuevo->cantidad = intval($item->cantidad);
                $nuevo->precio_compra = doubleval($item->precio_compra);
                $nuevo->total = doubleval($item->total);

                $nuevo->save();
                $this->actualizar_producto($item->producto_id, $item->cantidad, $item->precio_compra);
            }

            $detalles_save = DetalleCompra::where('compra_id', $compra_id)->get();

            $response = [
                'status' => true,
                'mensaje' => 'Se han guardado los productos',
                'detalle_compras' => $detalles_save,
            ];

        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay productos para guardar',
                'detalle_compras' => null,
            ];
        }
        
        return $response;
    }

    protected function actualizar_producto($id_producto, $stock, $precio_compra){
        $producto  = Producto::find($id_producto);

        $producto->stock += $stock;
        $producto->precio_compra = $precio_compra;
        $producto->margen = $producto->precio_venta - $producto->precio_compra;

        $producto->save();
    }

    
}