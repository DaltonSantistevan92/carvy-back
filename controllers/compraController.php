<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'controllers/detallecompraController.php';
require_once 'controllers/inventarioController.php';
require_once 'models/transaccionModel.php';
require_once 'models/compraModel.php';

class CompraController
{

    private $cors;
    private $db;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->db = new Conexion();
    }

    public function listar()
    {
        $this->cors->corsJson();
        $compras = Compra::where('estado', 'A')->get(); 
        //var_dump($compras);
        $response = [];

        foreach ($compras as $item) {
            $aux = [
                'compras' => $item,
                'proveedor_id' => $item->proveedor->id,
                'usuario_id' => $item->usuario->id,

            ];
            $response[] = $aux;
        }

        echo json_encode($response);
    }

    public function buscar($params)
    {

        $this->cors->corsJson();
        $idcompra = intval($params['id']);
        $buscar = Compra::find($idcompra);
        $response = [];

        if ($buscar) {

            foreach ($buscar->detalle_compra as $subbuscar) { 
                $subbuscar->producto;
            }

            $response = [
                'status' => true,
                'mensaje' => 'Existe',
                'compra' => $buscar,
                'proveedor_id' => $buscar->proveedor->id,
                'usuario_id' => $buscar->usuario->id,
                'usuario_persona' => $buscar->usuario->persona->id,
                'detalle_compra_id' => $buscar->detalle_compra,

            ];

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No esiste la compra',
                'compra' => null,
            ];
        }

        echo json_encode($response);

    }

    public function guardar(Request $request)
    {

        $this->cors->corsJson();
        $datacompra = $request->input("compra");
        $detalles_compras = $request->input("detalle_compras");

        $serie_documento = $datacompra->serie_documento;

        $response = [];

        if ($datacompra) {
            $datacompra->proveedor_id = intval($datacompra->proveedor_id);
            $datacompra->usuario_id = intval($datacompra->usuario_id);
            $datacompra->serie_documento = $datacompra->serie_documento;
            $datacompra->descuento = floatval($datacompra->descuento);
            $datacompra->sub_total = floatval($datacompra->sub_total);
            $datacompra->iva = floatval($datacompra->iva);
            $datacompra->total = floatval($datacompra->total);
 
            //Empieza
            $nuevo = new compra();
            $nuevo->proveedor_id = $datacompra->proveedor_id;
            $nuevo->usuario_id = $datacompra->usuario_id;
            $nuevo->serie_documento = $datacompra->serie_documento;
            $nuevo->descuento = $datacompra->descuento;
            $nuevo->sub_total = $datacompra->sub_total;
            $nuevo->iva = $datacompra->iva;
            $nuevo->total = $datacompra->total;
            $nuevo->estado = 'A';
            $nuevo->fecha_compra = date('Y-m-d');

            $existe = Compra::where('serie_documento', $serie_documento)->get()->first();

            if ($existe) {
                $response = [
                    'status' => false,
                    'mensaje' => 'La compra ya existe',
                    'compra' => null,
                    'detalle' => null,
                    'transaccion' => null,
                    'inventario' => null,
                ];
            } else {
                if ($nuevo->save()) {

                    //Guardar detalle de compras
                    $detalleController = new DetalleCompraController();  
                    $extra = $detalleController->guardar($nuevo->id, $detalles_compras); 

                    //Insertar una nueva transaccion
                    $nuevaTransaccion = $this->nueva_transaccion($nuevo);

                    //Actualizar el inventario
                    $inventarioController = new InventarioController; 
                    $resInvt = $inventarioController->guardar_ingreso_productos($nuevaTransaccion->id, $detalles_compras, 'E');

                    //Actualizar el precio de venta
                    $this->actualizarPrecioVenta($detalles_compras);

                    $response = [
                        'status' => true,
                        'mensaje' => 'Guardando los datos',
                        'compra' => $nuevo,
                        'detalle' => $extra,
                        'transaccion' => $nuevaTransaccion,
                        'inventario' => $resInvt,
                    ];

                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se pudo guardar, intente nuevamente',
                        'compra' => null,
                        'detalle' => null,
                        'transaccion' => null,
                        'inventario' => null,
                    ];
                }
            }

        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para procesar',
                'compra' => null,
                'detalle' => null,
                'transaccion' => null,
                'inventario' => null,
            ];}
 
        echo json_encode($response);
    }

    private function actualizarPrecioVenta($arrayProductos){
        
        if(count($arrayProductos) > 0){
            for($i = 0; $i < count($arrayProductos); $i++){
                $id = $arrayProductos[$i]->producto_id;
                
                $producto = Producto::find(intval($id));
                
                $lastInvt = Inventario::where('producto_id', $id)->orderBy('id', 'DESC')->get()->first();
                $producto->precio_venta = $lastInvt->precio_disponible;
                $producto->save();
            }
        }
    }
    
    protected function nueva_transaccion($nuevo)
    {
        $nuevaTransaccion = new Transaccion; 

        $nuevaTransaccion->tipo_movimiento = 'E';
        $nuevaTransaccion->fecha = date('Y-m-d');
        $nuevaTransaccion->descripcion = 'Compra con n° de serie ' . $nuevo->serie_documento;
        $nuevaTransaccion->compra_id = $nuevo->id;
        $nuevaTransaccion->save();

        return $nuevaTransaccion;
    }

    public function listarTabla($params)
    {
        $this->cors->corsJson(); 
        $opcion = $params['opcion'];
        $hoy = date('Y-m-d');
        $response = [];

        $data = [];
        $i = 1;

        switch ($opcion) {
            case 'hoy':
                $compras = Compra::where('fecha_compra', $hoy)
                    ->where('estado', 'A')
                    ->orderBy('id', 'DESC')
                    ->get();

                if (count($compras) > 0) {
                    foreach ($compras as $item) {
                        $item->proveedor->id;
                        $item->usuario->id;
                        $item->detalle_compra;
                        $item->usuario->persona->id;

                        foreach ($item->detalle_compra as $subitem) {
                            $subitem->producto;
                        }
                    }

                    $response = [
                        'estatus' => true,
                        'mensaje' => 'Existen datos',
                        'compras' => $compras,
                    ];
                } else {
                    $response = [
                        'estatus' => false,
                        'mensaje' => 'No hay datos para esta consulta',
                        'compras' => null,
                    ];
                }
                break;

            case 'ayer':
                $ayer = date("Y-m-d", strtotime($hoy . "- 1 days"));
                $compras = Compra::where('fecha_compra', $ayer)
                    ->where('estado', 'A')
                    ->orderBy('id', 'DESC')
                    ->get();

                if (count($compras) > 0) {
                    foreach ($compras as $item) {
                        $item->proveedor->id;
                        $item->usuario->id;
                        $item->detalle_compra;
                        $item->usuario->persona->id;

                        foreach ($item->detalle_compra as $subitem) {
                            $subitem->producto;
                        }
                    }

                    $response = [
                        'estatus' => true,
                        'mensaje' => 'Existen datos',
                        'compras' => $compras,
                    ];
                } else {
                    $response = [
                        'estatus' => false,
                        'mensaje' => 'No hay datos para esta consulta',
                        'compras' => null,
                    ];
                }
                break;

            case 'semana':
                $semana = date("Y-m-d", strtotime($hoy . "- 7 days"));
                $compras = Compra::where('fecha_compra', '>=', $semana)
                    ->where('fecha_compra', '<=', $hoy)
                    ->where('estado', 'A')
                    ->get();

                if (count($compras) > 0) {
                    foreach ($compras as $item) {
                        $item->proveedor->id;
                        $item->usuario->id;
                        $item->detalle_compra;
                        $item->usuario->persona->id;

                        foreach ($item->detalle_compra as $subitem) {
                            $subitem->producto;
                        }
                    }

                    $response = [
                        'estatus' => true,
                        'mensaje' => 'Existen datos',
                        'compras' => $compras,
                    ];
                } else {
                    $response = [
                        'estatus' => false,
                        'mensaje' => 'No hay datos para esta consulta',
                        'compras' => null,
                    ];
                }
                break;

            case 'mes':
                $mes = date('Y').'-'.date('m').'-01';
                $compras = Compra::where('fecha_compra', '>=', $mes)
                    ->where('fecha_compra', '<=', $hoy)
                    ->where('estado', 'A')
                    ->get();

                if (count($compras) > 0) {
                    foreach ($compras as $item) {
                        $item->proveedor->id;
                        $item->usuario->id;
                        $item->detalle_compra;
                        $item->usuario->persona->id;

                        foreach ($item->detalle_compra as $subitem) {
                            $subitem->producto;
                        }
                    }

                    $response = [
                        'estatus' => true,
                        'mensaje' => 'Existen datos',
                        'compras' => $compras,
                    ];
                } else {
                    $response = [
                        'estatus' => false,
                        'mensaje' => 'No hay datos para esta consulta',
                        'compras' => null,
                    ];
                }
                break;

            default:
                $response = [
                    'estatus' => false,
                    'mensaje' => 'Parámetro incorrecto',
                    'compras' => null,
                ];
        }

        //datatable
        foreach ($compras as $c) {

            $botones = '<div class="text-center">
                <button class="btn btn-sm btn-outline-primary" onclick="ir_compra_factura(' . $c->id . ')">
                    <i class="fas fa-eye"></i>
                </button>
            </div>';

            $data[] = [

                0 => $i,
                1 => $c->serie_documento,
                2 => $c->proveedor->razon_social,
                3 => number_format($c->total,2,'.',''),
                4 => $c->fecha_compra,
                5 => $botones,
            ];
            $i++;
        }
        $result = [
            'sEcho' => 1,
            'iTotalRecords' => count($data),
            'iTotalDisplayRecords' => count($data),
            'aaData' => $data,
        ];

        echo json_encode($result);
    }

    public function total(){
        $this->cors->corsJson();
        $meses = [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        ];
        $posMes = intval(date('m')) -1 ;
        // echo $meses[$posMes];
        $hoy = date('Y-m-d');
        $inicio_mes = date('Y').'-'.date('m').'-01';
        
        $compras = Compra::where('estado','A')
                    ->where('fecha_compra','>=', $inicio_mes)
                    ->where('fecha_compra', '<=', $hoy)  
                    ->get();
        
        $response = []; $total = 0;

        if($compras){
            foreach ($compras as $c) {
                $aux = $total += $c->total;
                $total = round($aux,2);
            }

            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'total' => $total,
                'mes' => $meses[$posMes]
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No existen datos',
                'total' => 0,
                'mes' => $meses[$posMes]
            ];
        }

        echo json_encode($response);
    }

    public function grafica_compra(){
        $this->cors->corsJson();
        $year = date('Y');

        $meses = [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        ];
        $data = [];

         //Obtener total de compras y ventas mensual
         for($i = 0; $i < count($meses); $i++){
            $sqlCompras = "SELECT SUM(total) as suma FROM `compras` WHERE MONTH(fecha_compra) = ($i + 1) AND estado = 'A'";
            
            $comprasMes = $this->db->database::select($sqlCompras);
    
            $compras = ($comprasMes[0]->suma) ? round($comprasMes[0]->suma, 2) : 0;
           
            $aux = [
                'x' => $meses[$i],
                'compras' => $compras
            ];
            array_push($data, $aux);
        }
        echo json_encode($data);

    }

    public function grafica_general(){
        $this->cors->corsJson();
        $year = date('Y');

        $meses = [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        ];
        $data = [];
        
        //Obtener total de compras y ventas mensual
        for($i = 0; $i < count($meses); $i++){
            $sqlCompras = "SELECT SUM(total) as suma FROM `compras` WHERE MONTH(fecha_compra) = ($i + 1) AND estado = 'A'";
            $sqlVentas = "SELECT SUM(total) as suma FROM `ventas` WHERE MONTH(fecha_venta) = ($i + 1) AND estado = 'A'";

            $comprasMes = $this->db->database::select($sqlCompras);
            $ventasMes = $this->db->database::select($sqlVentas);

            $compras = ($comprasMes[0]->suma) ? round($comprasMes[0]->suma, 2) : 0;
            $ventas  = ($ventasMes[0]->suma) ? round($ventasMes[0]->suma, 2) : 0;
           
            $aux = [
                'x' => $meses[$i],
                'compras' => $compras,
                'ventas' => $ventas
            ];
            array_push($data, $aux);
        }

        echo json_encode($data); 
    }
}