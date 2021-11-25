<?php

require_once 'app/cors.php';
require_once 'app/request.php';
require_once 'core/conexion.php';
require_once 'core/params.php';
require_once 'models/ventaModel.php';
require_once 'models/transaccionModel.php';
require_once 'controllers/detalleventaController.php';
require_once 'controllers/inventarioController.php';
require_once 'controllers/servicioController.php';
require_once 'app/helper.php';
require_once 'models/productoModel.php';
require_once 'models/servicioModel.php';

class VentaController
{
    private $cors;
    private $conexion;

    public function __construct()
    {
        $this->cors = new Cors();
        $this->conexion = new Conexion();
    }

    public function listar()
    {
        $this->cors->corsJson();
        $ventas = Venta::where('estado', 'A')->get();
        $response = [];

        foreach ($ventas as $item) {
            $aux = [
                'ventas' => $item,
                'cliente_id' => $item->cliente->persona->id,
                'usuario_id' => $item->usuario->id,
            ];
            $response[] = $aux;
        }

        echo json_encode($response);
    }

    public function buscar($params)
    {
        $this->cors->corsJson();
        $idventa = intval($params['id']);

        $buscar = Venta::find($idventa);
        $_servicio = Servicio::where('venta_id', $buscar->id)->get()->first();
        $servicio = ($_servicio == null) ? false: $_servicio;
        $orden = ($_servicio == null)  ? false : $servicio->orden;

        $response = [];

        if ($buscar) {
            foreach ($buscar->detalle_venta as $subbuscar) {
                $subbuscar->producto;
            }

            $response = [
                'status' => true,
                'mensaje' => 'Existe',
                'venta' => $buscar,
                'cliente_id' => $buscar->cliente->id,
                'cliente_persona' => $buscar->cliente->persona->id,
                'usuario_id' => $buscar->usuario->id,
                'usuario_persona' => $buscar->usuario->persona->id,
                'detalle_venta' => $buscar->detalle_venta,
                'servicio' => $servicio,
                'orden' => $orden,
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No Existe la venta',
                'venta' => null,
            ];
        }

        echo json_encode($response);
    }

    public function guardar(Request $request)
    {
        $this->cors->corsJson();

        $venta = $request->input('venta');
        $detalles_ventas = $request->input('detalles');
        $servicios = $request->input('servicio');

        $serie = $venta->serie;
        $response = [];

        if ($venta) {
            $venta->serie = $venta->serie;
            $venta->usuario_id = intval($venta->usuario_id);
            $venta->cliente_id = intval($venta->cliente_id);
            $venta->subtotal = floatval($venta->subtotal);
            $venta->iva = floatval($venta->iva);
            $venta->descuento_efectivo = floatval($venta->descuento_efectivo);
            $venta->total = floatval($venta->total);

            //Empieza
            $nuevo = new Venta();
            $nuevo->serie = $venta->serie;
            $nuevo->usuario_id = $venta->usuario_id;
            $nuevo->cliente_id = $venta->cliente_id;
            $nuevo->empresa_id = 1;
            $nuevo->subtotal = $venta->subtotal;
            $nuevo->iva = $venta->iva;
            //$nuevo->descueto_porcentaje= $venta->serie_documento;
            $nuevo->descuento_efectivo = $venta->descuento_efectivo;
            $nuevo->total = $venta->total;
            $nuevo->fecha_venta = date('Y-m-d');
            $nuevo->hora_venta = date('H:i:s');
            $nuevo->estado = 'A';

            $existe = Venta::where('serie', $serie)->get()->first();

            if ($existe) {
                $response = [
                    'status' => false,
                    'mensaje' => 'La venta ya existe',
                    'venta' => null,
                    'detalle' => null,
                    'transaccion' => null,
                    'inventario' => null,

                ];
            } else {
                if ($nuevo->save()) {

                    //cuando dispone de una orden
                    if ($servicios->orden_id != '0') {
                        $servicioCtr = new ServicioController;
                        $servicioCtr->guardar($servicios, $nuevo->id);
                        //var_dump($servicioCtr); die();
                    }

                    //Guarda detalle de venta
                    $detalleController = new DetalleVentaController();

                    $extra = $detalleController->guardar($nuevo->id, $detalles_ventas);

                    //Insertar una nueva transaccion
                    $nuevaTransaccion = $this->nueva_transaccion($nuevo);

                    //Actualizar el inventario
                    $inventarioController = new InventarioController;
                    $resInvt = $inventarioController->guardar_ingreso_productos($nuevaTransaccion->id, $detalles_ventas, 'S');

                    //Actualizar el precio de venta
                    $this->actualizarPrecioVenta($detalles_ventas);

                    $response = [
                        'status' => true,
                        'mensaje' => 'Guardando los datos',
                        'venta' => $nuevo,
                        'detalle' => $extra,
                        'transaccion' => $nuevaTransaccion,
                        'inventario' => $resInvt,
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'mensaje' => 'No se puede guardar',
                        'venta' => null,
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
                'venta' => null,
                'detalle' => null,
                'transaccion' => null,
                'inventario' => null,
            ];
        }
        // var_dump($venta); die();
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

        $nuevaTransaccion->tipo_movimiento = 'S';
        $nuevaTransaccion->fecha = date('Y-m-d');
        $nuevaTransaccion->descripcion = 'Venta con n° de serie ' . $nuevo->serie;
        $nuevaTransaccion->venta_id = $nuevo->id;
        $nuevaTransaccion->save();

        return $nuevaTransaccion;
    }

    public function listarTabla($params) 
    {
        $this->cors->corsJson();
        $opcion = $params['opcion'];
        $hoy = date('Y-m-d');

        $data = [];
        $i = 1;

        switch ($opcion) {
            case 'hoy':
                $ventas = Venta::where('fecha_venta', $hoy)
                    ->where('estado', 'A')
                    ->orderBy('id', 'DESC')
                    ->get();
                if (count($ventas) > 0) {
                    foreach ($ventas as $item) {
                        $item->cliente->id;
                        $item->usuario->id;
                        $item->detalle_venta;
                        $item->usuario->persona->id;

                        foreach ($item->detalle_venta as $subitem) {
                            $subitem->producto;
                        }
                    }
                    $response = [
                        'estatus' => true,
                        'mensaje' => 'Existen datos',
                        'compras' => $ventas,
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
                $ventas = Venta::where('fecha_venta', $ayer)
                    ->where('estado', 'A')
                    ->orderBy('id', 'DESC')
                    ->get();

                if (count($ventas) > 0) {
                    foreach ($ventas as $item) {
                        $item->cliente->id;
                        $item->usuario->id;
                        $item->detalle_venta;
                        $item->usuario->persona->id;

                        foreach ($item->detalle_venta as $subitem) {
                            $subitem->producto;
                        }
                    }
                    $response = [
                        'estatus' => true,
                        'mensaje' => 'Existen datos',
                        'compras' => $ventas,
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
                $ventas = Venta::where('fecha_venta', '>=', $semana)
                    ->where('fecha_venta', '<=', $hoy)
                    ->where('estado', 'A')
                    ->get();

                if (count($ventas) > 0) {
                    foreach ($ventas as $item) {
                        $item->cliente->id;
                        $item->usuario->id;
                        $item->detalle_venta;
                        $item->usuario->persona->id;

                        foreach ($item->detalle_venta as $subitem) {
                            $subitem->producto;
                        }
                    }
                    $response = [
                        'estatus' => true,
                        'mensaje' => 'Existen datos',
                        'compras' => $ventas,
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
                $mes = date('Y') . '-' . date('m') . '-01';
                $ventas = Venta::where('fecha_venta', '>=', $mes)
                    ->where('fecha_venta', '<=', $hoy)
                    ->where('estado', 'A')
                    ->get();

                if (count($ventas) > 0) {
                    foreach ($ventas as $item) {
                        $item->cliente->id;
                        $item->usuario->id;
                        $item->detalle_venta;
                        $item->usuario->persona->id;

                        foreach ($item->detalle_venta as $subitem) {
                            $subitem->producto;
                        }
                    }
                    $response = [
                        'estatus' => true,
                        'mensaje' => 'Existen datos',
                        'compras' => $ventas,
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
        foreach ($ventas as $v) {

            $botones = '<div class="text-center">
                <button class="btn btn-sm btn-outline-primary" onclick="ir_venta_factura(' . $v->id . ')">
                    <i class="fas fa-eye"></i>
                </button>
            </div>';

            $data[] = [

                0 => $i,
                1 => $v->serie,
                2 => $v->cliente->persona->nombres,
                3 => number_format($v->total, 2, '.', ''),
                4 => $v->fecha_venta,
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

    public function total()
    {
        $this->cors->corsJson();
        $meses = [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre',
        ];
        $posMes = intval(date('m')) - 1;
        //echo $meses[$posMes];
        $hoy = date('Y-m-d');
        $inicio_mes = date('Y') . '-' . date('m') . '-01';

        $ventas = Venta::where('estado', 'A')
            ->where('fecha_venta', '>=', $inicio_mes)
            ->where('fecha_venta', '<=', $hoy)->get();

        $response = [];
        $total = 0;

        if ($ventas) {
            foreach ($ventas as $v) {
                $aux = $total += $v->total;
                $total = round($aux, 2);
            }
            $response = [
                'status' => true,
                'mensaje' => 'Existen datos',
                'total' => $total,
                'mes' => $meses[$posMes],
            ];
        } else {
            $response = [
                'status' => false,
                'mensaje' => 'No Existen datos',
                'total' => 0,
                'mes' => $meses[$posMes],
            ];
        }
        echo json_encode($response);
    }

    public function comprafrecuentes($params)
    {

        $inicio = $params['inicio'];
        $fin = $params['fin'];
        $limit = intval($params['limit']);

        $ventas = Venta::where('fecha_venta', '>=', $inicio)
            ->where('fecha_venta', '<=', $fin)
            ->where('estado', 'A')
            ->take($limit)->get();

        $productos_id = []; //array principal
        $secundario = [];

        foreach ($ventas as $item) {
            $item->detalle_venta; //array    
            foreach ($item->detalle_venta as $detalle) {

                $aux = [
                    'id' => $detalle->producto_id,
                    'cantidad' => $detalle->cantidad,
                ];

                $productos_id[] = (object)$aux;
                $secundario[] = $detalle->producto_id;
            }
        }

        $no_repetidos = array_values(array_unique($secundario));
        $nuevo_array = [];
        $contador = 0;

        //Algoritmo para contar y eliminar los elementos repetidos de un array
        for ($i = 0; $i < count($no_repetidos); $i++) {
            foreach ($productos_id as $item) {
                if ($item->id === $no_repetidos[$i]) {
                    $contador += $item->cantidad;
                }
            }
            $aux = [
                'producto_id' => $no_repetidos[$i],
                'cantidad' => $contador
            ];

            $contador = 0;
            $nuevo_array[] = (object)$aux;
            $aux = [];
        }

        $array_productos = $this->ordenar_array($nuevo_array);
        $array_productos = Helper::invertir_array($array_productos);

        $array_seudoFinal = [];
        //Recortar segun limite
        if(count($array_productos) < $limit){
            $array_seudoFinal = $array_productos;
        }else
        if(count($array_productos) == $limit){
            $array_seudoFinal = $array_productos;
        }else
        if(count($array_productos) > $limit){
            for($i = 0; $i < $limit; $i++){
                $array_seudoFinal[] = $array_productos[$i];
            }
        }

        $arrayFinal = [];   $total_global = 0;  $totalParcentaje = 0;

        foreach($array_seudoFinal as $item){
            $p = Producto::find($item->producto_id);
            $total = $p->precio_venta * $item->cantidad;
            $total_global += $total;
            $totalParcentaje +=  $item->cantidad;

            $aux = [
                'producto' => $p,
                'cantidad' => $item->cantidad,
                'total' => $total
            ];
            $arrayFinal[] = (object)$aux;
        }

        //Armar data de grafico de pastel para cantidad productos mas vendidos
        //Armar la data de grafico pastel por porcentaje
        $masVendidos = [];  $labels = [];   $porcentajes = [];

        foreach($arrayFinal as $item){

            $labels[] = $item->producto->nombre;
            $masVendidos[] = $item->cantidad;
            $p = round((100 * $item->cantidad) / $totalParcentaje,2);
            $porcentajes[] = $p;
        }


        $response = [
            'lista' => $arrayFinal,
            'data' => [
                'masVendidos' => [
                    'data' => $masVendidos,
                    'labels' => $labels
                ],
                'porcentajes' => [
                    'data' => $porcentajes,
                    'labels' => $labels
                ]
            ],
            'total_general' => $total_global
        ];

        echo json_encode($response);
    }

    function ordenar_array($array){
        for ($i = 1; $i < count($array); $i++) {
            for ($j = 0; $j < count($array) - $i; $j++) {
                if ($array[$j]->cantidad > $array[$j + 1]->cantidad) {

                    $k = $array[$j + 1];
                    $array[$j + 1] = $array[$j];
                    $array[$j] = $k;
                }
            }
        }

        return $array;
    }

    function ventaMensuales($params){

        $inicio = $params['inicio'];
        $fin = $params['fin'];
        $meses = Helper::MESES(); 

        $inicio  = new DateTime($inicio);
        $fin = new DateTime($fin);

        $mesInicio = intval(explode('-',$params['inicio'])[1]);
        $mesFin = intval(explode('-',$params['fin'])[1]);

        $data = []; $labels = [];   $dataTotal = [];    $dataIva = [];  $dataSubtotal = [];
        $totalGeneral = 0;  $ivaGeneral = 0;    $subtotalGeneral = 0;
       
        for($i = $mesInicio; $i <= $mesFin; $i++){
            $sql = "SELECT SUM(total) as total, SUM(subtotal) as subtotal, SUM(iva) as iva, fecha_venta FROM `ventas` WHERE MONTH(fecha_venta) = ($i) AND estado = 'A'";
            $ventasMes = $this->conexion->database::select($sql)[0];
            
            $iva = (isset($ventasMes->iva)) ? (round($ventasMes->iva,2)) : 0;
            $subtotal = (isset($ventasMes->subtotal)) ? (round($ventasMes->subtotal, 2)) : 0;
            $total = (isset($ventasMes->total)) ? (round($ventasMes->total, 2)) : 0;
            $fecha = (isset($ventasMes->fecha_venta)) ? $ventasMes->fecha_venta: '-'; 
            $serie = 'Total de ventas para '.$meses[$i-1];

            $aux = [
                'fecha' => $fecha,
                'serie' => $serie,
                'iva' => $iva,
                'subtotal' => $subtotal,
                'total' => $total
            ];
            $aux2 = [
                'mes' => $meses[$i],
                'data' => $aux
            ];
            $data[] = $aux2;
            $labels[] = ucfirst($meses[$i-1]);
            $dataTotal[] = $total;
            $dataIva[] = $iva;
            $dataSubtotal[] = $subtotal;
            $totalGeneral += $total;
            $ivaGeneral += $iva;
            $subtotalGeneral += $subtotal;
        }

        $auxPorcenteje = (100 * $subtotalGeneral) / $totalGeneral;
        $porcentajeSubototal = round($auxPorcenteje,2);
        $porcentajeIva = round((100 - $porcentajeSubototal),2);
        $ivaGeneral= round($ivaGeneral,2);
        $response = [
            'lista' => $data,
            'totales' => [
                'total' => $totalGeneral,
                'iva' => $ivaGeneral,
                'subtotal' => $subtotalGeneral
            ],
            'barra' => [
                'labels' => $labels,
                'dataTotal' => $dataTotal,
                'dataSubtotal' => $dataSubtotal,
                'dataIva' => $dataIva
            ],
            'porcentaje' => [
                'labels' => ['Iva', 'Subtotal'],
                'data' => [$porcentajeIva, $porcentajeSubototal]
            ]
        ];

        echo json_encode($response);
    }

    function ventaMensualCategoria($params){

        $this->cors->corsJson();

        $inicio = $params['inicio'];
        $fin = $params['fin'];
        $categoria_id = intval($params['categoria_id']);

        $ventas = Venta::where('fecha_venta', '>=', $inicio)
                    ->where('fecha_venta', '<=', $fin)->get();

        $productos = [];    $total_general = 0; $cantidad_general = 0;
        
        if(count($ventas) > 0){
            if($categoria_id == -1){
                foreach($ventas as $item){
                    $array = $item->detalle_venta;

                    foreach($array as $dv){
                        $aux = [
                            'producto' => $dv->producto,
                            'cantidad' => $dv->cantidad,
                            'precio_venta' => $dv->precio_venta,
                            'total' => $dv->total
                        ];

                        $productos[] = (object)$aux;
                        $cantidad_general += $dv->cantidad;
                        $total_general += $dv->total;
                    }
                }
            }else{
                foreach($ventas as $item){
                    $array = $item->detalle_venta;

                    foreach($array as $dv){

                        if($dv->producto->categoria_id == $categoria_id){
                            $aux = [
                                'producto' => $dv->producto,
                                'cantidad' => $dv->cantidad,
                                'precio_venta' => $dv->precio_venta,
                                'total' => $dv->total
                            ];
                            
                            $productos[] = (object)$aux;
                            $cantidad_general += $dv->cantidad;
                            $total_general += $dv->total;
                        }
                    }
                }
            }

            $response = [
                'status' => true,
                'mensaje' => 'Datos procesados',
                'data' => $productos,
                'total_general' => $total_general,
                'cantidad_general' => $cantidad_general
            ];
        }else{
            $response = [
                'status' => false,
                'mensaje' => 'No hay datos para la consulta',
                'data' => [],
                'total_general' => 0,
                'cantidad_general' => 0
            ];
        }

        echo json_encode($response);
    }

    public function proyeccion($params){

        $this->cors->corsJson();
        $year = intval($params['year']);
        $tabla = [];    $response = [];     $burbuja = [];  $radio = 5; $labels = [];
        
        $ventas = Venta::whereYear('created_at',$year)->get();      //Obtener todas las ventas anuales
        $fecha_min = $ventas[0]->fecha_venta;                       //Obtener fecha inicio de ventas
        $fecha_max = $ventas[count($ventas) - 1]->fecha_venta;      //Obtener fecha de la última venta

        $date1 = new DateTime($fecha_min);                          //Parseamos la fecha de inicio en objet Datetime
        $date2 = new DateTime($fecha_max);                          //Parseamos la fecha de fin en objet Datetime
        $diff = $date1->diff($date2);                               //Hacer la resta o diferencia de las fechas
        $dias = $diff->days;                                        //Obtener la diferencia en días

        if(count($ventas) > 0){
           for($i = 0; $i <= $dias; $i++){                          //Recorrer el numero de días
            $sumDay = "+ ".($i)." days";                            //Armar el string para sumar de (1 días) y contando
            $fc = date("Y-m-d",strtotime($fecha_min.$sumDay));      //Sumar el numero de dias según el contador
            $labels[] = $fc;                                        //Guardar la nueva fecha y guardar en un array
            
            $ventaDia = Venta::where('fecha_venta', $fc)->get();    //Obtener la venta de la fecha específica - intervalo de fecha inicio y maxima
            
            if($ventaDia && count($ventaDia) > 0){                  //Validar si existe la venta
                $cant = 0;  $total = 0;                             //Iniciar las variables
                foreach($ventaDia as $vd){                          //Recorrer las ventas de la fecha especifica
                    $cant++;                                        //Aumentar la cantidad 
                    $total += $vd->total;                           //Sumar el total de la venta
                }
                
                $total = round($total, 2);                          //Redondear en dos cifras
                $auxDia = [                                         //Armar el array asociativo
                    'fecha' => $fc,
                    'cantidad' => $cant,    //x
                    'venta' => $total       //y
                ];

                $tabla[] = (object)$auxDia;                         //Parsear un objeto el array asociativo e insertarlo en un nuevo array
                $cant = 0;  $total = 0;                             //Resetear las variables
            }
           }

           $it = 1;
           foreach($tabla as $t){                                   //Recorrer los datos de todas las ventas
                $auxB = [                                           //Armar el nuevo objeto
                    'x' => $it,
                    'y' => $t->venta,
                    'r' => $t->cantidad
                ];

                $it++;
                $burbuja[] = (object)$auxB;                         //Insertar datos en el array para armar la data
           }

           $full = [];  $i = 0; $sumax2 = 0;    $sumaxy = 0;    $sumax = 0; $sumay = 0;

           foreach($tabla as $t){                                   //Recorrer todas las ventas
                $x2 = pow($t->cantidad, 2);                         //Elevar x o cantidad al cuadrado
                $xy = round($t->cantidad * $t->venta, 2);           //Elevar x*y o la cantidad total de ventas por el numero de dias al cuadrado

                $sumax += $t->cantidad;     $sumay += $t->venta;    //Sumar x --- sumar y -> sumadores
                $sumax2 += $x2;     $sumaxy += $xy;                 //sumar x al cuadrado, 

                $aux = [                                            //Armar el array asociativo 
                    'venta' => ($i + 1),                            //x
                    'cantidad' => $t->cantidad,                     //Cantidad de ventas de ese día
                    'total' => $t->venta,                           //y
                    'x2' => $x2,                                    // x al cuadrado
                    'xy' => $xy                                     //x*y
                ];

                $full[] = (object)$aux;                             //Guardar en un nuevo array
           }
           $n = count($tabla);                                      //Guardar el numero de datos a procesar

           $xPromedio = round(($sumax / $n),2);                     //Obtener el promedio de x
           $yPromedio = round(($sumay / $n),2);                     //Obtener el promedio de y

           $b = ($sumaxy - $n*$xPromedio*$yPromedio) / ($sumax2 - $n*(pow($xPromedio,2)));  //Calcular la constante b
           $a = $yPromedio - $b*$xPromedio;                         //Calcular la constante a -> formula ypromedio - b* xpromedio
           
           $b = round($b,2);    $a = round($a,2);                   //Redondear a dos decimales la constantes
           $singo = ($b > 0) ? '+' : '-';                           //Obtner el signo de la constante b
           $ecuacion = (string)$a.$singo.$b.'*x';                   //Armar la ecuacion en forma de un string

           $response = [
               'status' => true,
               'tabla' => $tabla,
               'burbuja' => [
                   'data' => $burbuja,
                   'labels' => $labels
               ],
               'data' => [
                   'tabla' => $full,
                   'promedio' => [
                       'x' => $xPromedio,
                       'y' => $yPromedio
                   ],
                   'sumatoria' => [
                       'sumax2' => $sumax2,
                       'sumaxy' => $sumaxy
                   ],
                   'constantes' => [
                       'b' => $b,
                       'a' => $a
                   ],
                   'signo' => $singo,
                   'ecuacion' => $ecuacion
               ]
           ];
        }else{
            $response = [
                'status' => false,
                'tabla' => [],
                'burbuja' => false
            ];
        }

        echo json_encode($response);
    }
}
