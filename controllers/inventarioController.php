<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'models/inventarioModel.php';

class InventarioController
{

    private $cors;
    private $db;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->db = new Conexion();
    }

    public function kar($params){
        $this->cors->corsJson(); 

        $id_producto = intval($params['id_producto']);
        $desde = $params['desde'];
        $hasta = $params['hasta'];

        $dataInventario = Inventario::where('producto_id', $id_producto)
            ->where('fecha', '>=', $desde)
            ->where('fecha', '<=', $hasta)
            ->get();

            $entrada[0] = 0;     $salida [0] = 0;
            $entrada[1] = 0;     $salida [1] = 0;
            $entrada[2] = 0;     $salida [2] = 0;

        foreach ($dataInventario as $di) {
            $di->producto;
            $di->transaccion->tipo_movimiento;

            if($di->transaccion->tipo_movimiento == 'E'){
                    $entrada[0] += $di->cantidad;
                    $entrada[1] = $di->precio;
                    $entrada[2] = $di->total;      
            }else{
                    $salida[0] += $di->cantidad;
                    $salida[1] = $di->precio;
                    $salida[2] = $di->total;
            }
        }

        $salida[0] = abs($salida[0]);
        $pos = count($dataInventario)-1;

        $disponible[0] = abs($dataInventario[$pos]->cantidad_disponible);
        $disponible[1] = abs($dataInventario[$pos]->precio_disponible);
        $disponible[2] = abs($dataInventario[$pos]->total_disponible);

        if(count($dataInventario) > 0){
            $response = [
                'status' => true,
                'entrada' => $entrada,
                'salidas'=> $salida, 
                'disponibles' => $disponible,
            ];

        }else{
            $response = [
                'status' => false,
                'entrada' => null,
                'salidas'=> null, 
                'disponibles' => null,
            ];
        }

        echo json_encode($response);
    }

    public function kardex($params)
    {
        $this->cors->corsJson();

        $id_producto = intval($params['id_producto']);
        $desde = $params['desde'];
        $hasta = $params['hasta'];

        $dataInventario = Inventario::where('producto_id', $id_producto)
            ->where('fecha', '>=', $desde)
            ->where('fecha', '<=', $hasta)
            ->get();
        $data = [];
        $i = 1;

        foreach ($dataInventario as $di) {
            $di->producto;
            $di->transaccion->tipo_movimiento;

            $entrada = [];
            $salida = [];
            $tipo = '';

            if($di->transaccion->tipo_movimiento == 'E'){
                $entrada =[
                    0 => $di->cantidad,
                    1 => $di->precio,
                    2 => $di->total];

                $salida =[
                    0 => '',
                    1 => '',
                    2 => ''];
                    $tipo = 'Entrada';    
            }else{
                $salida =[
                    0 => $di->cantidad,
                    1 => $di->precio,
                    2 => $di->total];

                $entrada =[
                    0 => '',
                    1 => '',
                    2 => ''];
                    $tipo = 'Salida';    

            }

            $data [] = [
                0 => $i,
                1 => $di->fecha,
                2 => $tipo,
                3 => $entrada[0],
                4 => $entrada[1],
                5 => $entrada[2],
                6 => $salida[0],
                7 => $salida[1],
                8 => $salida[2],
                9 => $di->cantidad_disponible,
                10 => $di->precio_disponible,
                11 => ($di->total_disponible),
            ];
            $i++;      
        }
        $result = [
            'sEcho' => 1,
            'iTotalRecords' => count($dataInventario),
            'iTotalDisplayRecords' => count($dataInventario),
            'aaData' => $data,
        ];

        echo json_encode($result);
    }

    public function guardar_ingreso_productos($id_transaccion, $detalles = [], $tipo)
    {

        $response = [];
        $extra = [];

        if (count($detalles) > 0) {
            foreach ($detalles as $item) {
                $nuevo = new Inventario;

                $producto_id = intval($item->producto_id);
                $aux = intval($item->cantidad);
                $total = doubleval($item->total);
                

                $precio_compra = ($tipo == 'E') ? doubleval($item->precio_compra): doubleval($item->precio_venta);
                $cantidad = ($tipo == 'E') ? $aux : ((-1) * $aux);

                $nuevo->transaccion_id = intval($id_transaccion);
                $nuevo->producto_id = $producto_id;
                $nuevo->precio = $precio_compra;
                $nuevo->fecha = date('Y-m-d');
                $nuevo->cantidad = $cantidad;
                $nuevo->total = $total;
                $nuevo->tipo = $tipo;

                //1.- Verificar si existe un registro anterior del producto
                $existe = Inventario::where('producto_id', $producto_id)->get()->count();

                if ($existe == 0) { //Primer registro
                    $nuevo->cantidad_disponible = $cantidad;
                    $nuevo->precio_disponible = $precio_compra;
                    $nuevo->total_disponible = $total;

                    $extra = $this->tipo_inventario_first($tipo, $nuevo);
                } else { //Segundo  o más registros
                    $extra = $this->tipo_inventario_mas_registro($tipo, $nuevo);
                }
            }

            $response = [
                'status' => true,
                'mensaje' => 'Inventario actualizado correctamente',
                'extra' => $extra,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No se ha actualizado el inventario',
            ];
        }

        return $response;
    }

    private function tipo_inventario_first($tipo, Inventario $inventario)
    {
        $response = [];

        if ($tipo == 'E') {
            //guardar
            $inventario->save();

            $response = [
                'status' => true,
                'mensaje' => 'Primer movimiento del producto ' . $inventario->producto_id,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No hay productos en stock disponible, realice compras',
            ];
        }

        return $response;
    }

    private function tipo_inventario_mas_registro($tipo, Inventario $inventario)
    {

        $response = [];
        $last = Inventario::where('producto_id', $inventario->producto_id)
            ->orderBy('id', 'DESC')->get()->first();

        $cantidad = $inventario->cantidad + $last->cantidad_disponible;
        $inventario->cantidad_disponible = $cantidad;

        if ($tipo == 'E') { //Entrada
            $total = $last->total_disponible + $inventario->total;
            $inventario->total_disponible = $total;
        } else {
            $total = $last->total_disponible - $inventario->total;
            $inventario->total_disponible = $total;
        }

        $inventario->precio_disponible = round(($inventario->total_disponible / $cantidad), 2);

        if ($inventario->save()) {
            $response = [
                'status' => true,
                'mensaje' => 'Inventario actualizado ' . $inventario->producto_id,
                'inventario' => $inventario,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No se pudo actualizar el inventario',
                'inventario' => $inventario,
            ];
        }

        return $response;
    }
}
